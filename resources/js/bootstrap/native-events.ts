import { emitter } from '@/utils/eventBus';

if (!window.__nativeEventsRegistered) {
    window.__nativeEventsRegistered = true;

    Native.on("App\\Events\\DownloadOutput", (payload) => {
        console.log('zex-test-output')
        emitter.emit('download-output', payload);
    });

    Native.on("App\\Events\\FileAddedToQueue", (payload) => {
        console.log('zex-test-file')
        emitter.emit('file-added-to-queue', payload);
    });

    Native.on("App\\Events\\DownloadFinished", (payload) => {
        console.log('zex-test-finished')
        emitter.emit('download-finished', payload);
    });

    Native.on("App\\Events\\RequestStatusUpdated", (payload) => {
        console.log('zex-test-request')
        emitter.emit('request-status-updated', payload);
    });
}
