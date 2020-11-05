const site_id = 1

if (window.Notification) {
    Notification.requestPermission().then(function (permission) {
        pushSubsribe()
    });
}

function pushSubsribe() {
    //console.log('send push')
     if ('serviceWorker' in navigator) {
         navigator.serviceWorker.register('/sw.js').then(() => {
            navigator.serviceWorker.ready.then(registration => {
                registration.pushManager.subscribe({
                    userVisibleOnly: !0,
                    applicationServerKey: urlB64ToUint8Array(process.env.MIX_PUSH_PUBLIC_KEY)
                }).then(pushSubscription => {
                    sendSubscriptionToBackEnd(pushSubscription)
                }, error => {
                    console.log(error)
                })
            })
        }).catch(function(err) {
            // Регистрация не успешна
            console.log('ServiceWorker registration failed: ', err);
         });
     }
}

function sendSubscriptionToBackEnd(subscription) {
    const formData = new FormData()
    formData.append('site_id', site_id)
    formData.append('referer', window.location.host)
    formData.append('endpoint', subscription.endpoint)
    formData.append('p256dh', subscription.toJSON().keys.p256dh)
    formData.append('auth', subscription.toJSON().keys.auth)
    let date = new Date()
    let timezone = date.getTimezoneOffset() / 60
    formData.append('timezone', timezone)
    fetch(process.env.MIX_PUSH_API_URL + '/subscription/', {
        method: 'POST',
        mode: 'no-cors',
        header: {
            'content-type': 'application/json'
        },
        body: formData
    })
}

function urlB64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
