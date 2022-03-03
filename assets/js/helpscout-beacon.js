/*
* @Author: Benjamin Pelto
*/

!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});

window.Beacon('config', {
  color: stylehelperHelpscout.color,
  enableFabAnimation: false,
  text: stylehelperHelpscout.translations.text,
  labels: {
    sendAMessage: stylehelperHelpscout.translations.sendAMessage,
    howCanWeHelp: stylehelperHelpscout.translations.howCanWeHelp,
    responseTime: stylehelperHelpscout.translations.responseTime,
    continueEditing: stylehelperHelpscout.translations.continueEditing,
    lastUpdated: stylehelperHelpscout.translations.lastUpdated,
    you: stylehelperHelpscout.translations.you,
    nameLabel: stylehelperHelpscout.translations.nameLabel,
    subjectLabel: stylehelperHelpscout.translations.subjectLabel,
    emailLabel: stylehelperHelpscout.translations.emailLabel,
    messageLabel: stylehelperHelpscout.translations.messageLabel,
    messageSubmitLabel: stylehelperHelpscout.translations.messageSubmitLabel,
    next: stylehelperHelpscout.translations.next,
    weAreOnIt: stylehelperHelpscout.translations.weAreOnIt,
    messageConfirmationText: stylehelperHelpscout.translations.messageConfirmationText,
    wereHereToHelp: stylehelperHelpscout.translations.wereHereToHelp,
    whatMethodWorks: stylehelperHelpscout.translations.whatMethodWorks,
    viewAndUpdateMessage: stylehelperHelpscout.translations.viewAndUpdateMessage,
    previousMessages: stylehelperHelpscout.translations.previousMessages,
    messageButtonLabel: stylehelperHelpscout.translations.messageButtonLabel,
    noTimeToWaitAround: stylehelperHelpscout.translations.noTimeToWaitAround,
    addReply: stylehelperHelpscout.translations.addReply,
    addYourMessageHere: stylehelperHelpscout.translations.addYourMessageHere,
    sendMessage: stylehelperHelpscout.translations.sendMessage,
    received: stylehelperHelpscout.translations.received,
    waitingForAnAnswer: stylehelperHelpscout.translations.waitingForAnAnswer,
    previousMessageErrorText: stylehelperHelpscout.translations.previousMessageErrorText,
    justNow: stylehelperHelpscout.translations.justNow,
  },
});

window.Beacon('identify', {
  name: stylehelperHelpscout.userName,
  email: stylehelperHelpscout.userEmail,
  Site: stylehelperHelpscout.site,
  'Site URL': stylehelperHelpscout.siteUrl,
});

window.Beacon('prefill', {
  subject: stylehelperHelpscout.translations.prefilledSubject,
});

window.Beacon('init', '7658270b-a910-4616-95f7-ef5f78767424');
