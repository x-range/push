const site_id = 1

self.addEventListener('install', function(event) {
    self.skipWaiting()
})

self.addEventListener('activate', function(event) {
    event.waitUntil(
        self.registration.pushManager.subscribe({
            userVisibleOnly: !0,
            applicationServerKey: urlB64ToUint8Array(process.env.MIX_PUSH_PUBLIC_KEY)
        })
            .then(function(subscription) {
                    if (!subscription) {
                    return null;
                } else {
                    sendSubscriptionToBackEnd(subscription)
                }
            })
            .catch(function(error) { console.log(error) })
    )
})

self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return
    }

    const sendNotification = function(message) {

        return self.registration.showNotification(message.title, {
            body: message.body,
            icon: message.icon,
            image: message.image,
            badge: message.badge,
            data: message.data,
            tag: message.tag
        })
    }

    if (event.data) {
        let pushData = event.data.json()
        const formData = new FormData();
        formData.append('message_id', pushData.data.message_id)
        formData.append('subscriber_id', pushData.data.subscriber_id)
        fetch(process.env.MIX_PUSH_API_URL + '/statistic/notification', {
            method: 'POST',
            mode: 'no-cors',
            body: formData
        })
        event.waitUntil(
            sendNotification(pushData)
        )
    }
})


self.addEventListener('notificationclick', function (event) {
    event.notification.close()

    const formData = new FormData();
    formData.append('message_id', event.notification.data.message_id)
    formData.append('subscriber_id', event.notification.data.subscriber_id)
    fetch(process.env.MIX_PUSH_API_URL + '/statistic/click', {
        method: 'POST',
        mode: 'no-cors',
        body: formData
    })

    event.waitUntil(clients.matchAll({
        type: "window"
    }).then(function(clientList) {
        for (let i = 0; i < clientList.length; i++) {
            let client = clientList[i]
            if (client.url == event.notification.tag && 'focus' in client)
                return client.focus()
        }
        if (clients.openWindow)
            return clients.openWindow(event.notification.data.link)
    }))
})

self.addEventListener("notificationclose", event => {
    const formData = new FormData();
    formData.append('message_id', event.notification.data.message_id)
    formData.append('subscriber_id', event.notification.data.subscriber_id)
    fetch(process.env.MIX_PUSH_API_URL + '/statistic/close', {
        method: 'POST',
        mode: 'no-cors',
        body: formData
    })
})

self.addEventListener("pushsubscriptionchange", event => {
    event.waitUntil(self.registration.pushManager.subscribe({
            userVisibleOnly: !0,
            applicationServerKey: urlB64ToUint8Array(process.env.MIX_PUSH_PUBLIC_KEY)
        })
        .then(subscription => {
            sendSubscriptionToBackEnd(pushSubscription)
        })
    )
})

self.addEventListener('fetch', function onFetch(event) {
    return true;
})

function sendSubscriptionToBackEnd(subscription) {
    const formData = new FormData()
    formData.append('site_id', site_id)
    formData.append('referer', 'resubscribe')
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

    const rawData = atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
