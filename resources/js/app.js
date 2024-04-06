import './bootstrap';

document.addEventListener('DOMContentLoaded', (e) => {
    Echo.channel('chat').listen('MessageSent',(e)=>{
        console.log(e);
    });
});

document.addEventListener('DOMContentLoaded', (e) => {
    Echo.channel('online-status').listen('UserOnlineEvent',(e)=>{
        console.log(e);
    });
});
