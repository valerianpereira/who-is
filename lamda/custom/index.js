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
const HELP_MESSAGE = 'You can say tell me our pledge, or, you can say stop... What can I help you with?';
const HELP_REPROMPT = 'What can I help you with?';
const STOP_MESSAGE = 'Goodbye!';
const GREETMSG = 'Hello, would you like to learn the pledge or morning prayer? Say, teach me the morning prayer ? ';
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
    'getPledge': function () {
        this.response.cardRenderer(SKILL_NAME, `Indian Pledge`);
        this.response.speak(`Starting in 2 seconds. Repeat after me. <break time="3s"/>India is my country. <break time="1.5s"/>All Indians are my brothers and sisters. <break time="1.5s"/>
I love my country <break time="1.5s"/>and I am proud of its rich and varied heritage. <break time="1.5s"/>
I shall always strive <break time="1.5s"/>to be worthy of it. <break time="1.5s"/>
I shall give my parents, <break time="1.5s"/>teachers <break time="1s"/>and all elders respect <break time="1.5s"/>and treat everyone <break time="1s"/>with courtesy. <break time="1.5s"/>
To my country <break time="1s"/>and my people, <break time="1s"/>I pledge my devotion. <break time="1.5s"/>
In their well being <break time="1.5s"/>and prosperity alone, <break time="1.5s"/>lies my happiness. <break time="2s"/>Hope that was helpful. Have a nice time.`);
        this.emit(':responseReady');
    },
    'getMorningPrayer': function () {
        this.response.cardRenderer(SKILL_NAME, `Morning Prayer`);
        this.response.speak(`Starting in 2 seconds. Repeat after me. <break time="3s"/>God Almighty, <break time="1s"/>Our Heavenly Father, <break time="1s"/>We adore you, <break time="1s"/>and we offer ourselves <break time="1s"/>to your loving protection.<break time="1s"/>
Enlighten our hearts, <break time="1s"/>Strengthen our memory, <break time="1s"/>Direct our will, <break time="1s"/>towards what is right.<break time="1s"/>
Grant us the grace <break time="1s"/>to seek truth always. <break time="2s"/>Hope that was helpful. Have a nice time.`);
        this.emit(':responseReady');
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
