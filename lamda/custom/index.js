/* eslint-disable  func-names */
/* eslint quote-props: ["error", "consistent"]*/
/**
 * This sample demonstrates a simple skill built with the Amazon Alexa Skills
 * nodejs skill development kit.
 * This sample supports multiple lauguages. (en-US, en-GB, de-DE).
 * The Intent Schema, Custom Slots and Sample Utterances for this skill, as well
 * as testing instructions are located at 
 * githubLink
 **/

'use strict';
const Alexa = require('alexa-sdk');
const https = require("https");

//=========================================================================================================================================
//TODO: The items below this comment need your attention.
//=========================================================================================================================================

//Replace with your app ID (OPTIONAL).  You can find this value at the top of your skill's page on http://developer.amazon.com.
//Make sure to enclose your value in quotes, like this: const APP_ID = 'amzn1.ask.skill.bb4045e6-b3e8-4133-b650-72923c5980f1';
const APP_ID = undefined;

const SKILL_NAME = 'Who Is';
const HELP_MESSAGE = 'You can say tell me who owns webgeeks.in, or, you can say stop... What can I help you with?';
const HELP_REPROMPT = 'What can I help you with?';
const STOP_MESSAGE = 'Goodbye!';
const GREETMSG = 'Hello, would you like to learn who owns the domain webgeeks.in ? Say, give me information about the domain webgeeks.in ? ';
const REPROMPTMSG = 'Would you like to hear some other info ?';

//=============================================================
//Editing anything below this line might break your skill.
//=============================================================

const handlers = {
    'LaunchRequest': function () {
        this.emit('GreetUser');
    },
    'GreetUser': function () {
        this.response.cardRenderer(SKILL_NAME, GREETMSG);
        this.response.speak(GREETMSG).listen(GREETMSG);
        this.emit(':responseReady');
    },
    'GetWhoIsInfo': function () {
        const varrNa = this.event.request.intent.slots.domainName.value;
        if (this.event.request.intent.slots.domainName.value == '') {
            this.response.cardRenderer(SKILL_NAME, "Invalid Domain Name");
            this.response.speak("Invalid Domain Name");
            this.emit(':responseReady');
        } else {
            let speechOutput = "";

            https.get('https://valerianpereira.in/api/whois/who-is-info.php?domain=' + varrNa, res => {
                res.setEncoding("utf8");
                let body = "";
                let cardInfo = "";
                
                res.on("data", data => {
                    body += data;
                });
                res.on("end", () => {
                    body = JSON.parse(body);
                    
                    if (body.error.message != '') {
                        speechOutput = body.error.message + ' ';
                        cardInfo = body.error.message + ' ';
                    } else {
                        speechOutput = speechOutput + 'The domain '+body.name+' was registered on '+body.created+' and expires on '+body.expires+'. '+body.name+' is owned by '+body.contacts.owner.organization+' located at '+body.state+'. '+body.name+' is currently having the nameservers as '+body.nameservers+' and registered by '+body.registrar.name;
                        cardInfo = speechOutput + "\n\n";
                    }
                    
                    this.response.cardRenderer(SKILL_NAME + 'Domain Info', speechOutput);
                    this.response.speak(speechOutput);
                    this.emit(':responseReady');
                });
            });
        }
    },
    'AMAZON.HelpIntent': function () {
        const speechOutput = HELP_MESSAGE;
        const reprompt = HELP_REPROMPT;
        this.response.speak(speechOutput).listen(reprompt);
        this.emit(':responseReady');
    },
    'SessionEndedRequest': function () {
        this.emit(':tell', STOP_MESSAGE);
    },
    'EndSessionIntent': function () {
        this.emit(':tell', STOP_MESSAGE);
    },
    'AMAZON.CancelIntent': function () {
        this.response.speak(STOP_MESSAGE);
        this.emit(':responseReady');
    },
    'AMAZON.StopIntent': function () {
        this.response.speak(STOP_MESSAGE);
        this.emit(':responseReady');
    },
};

exports.handler = function (event, context, callback) {
    const alexa = Alexa.handler(event, context, callback);
    alexa.APP_ID = APP_ID;
    alexa.registerHandlers(handlers);
    alexa.execute();
};
