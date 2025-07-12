import { emitter } from '@/utils/eventBus';

if (!window.__nativeEventsRegistered) {
    window.__nativeEventsRegistered = true;

    Native.on("App\\Events\\DownloadOutput", (payload) => {
        emitter.emit('download-output', payload);
    });

    Native.on("App\\Events\\FileAddedToQueue", (payload) => {
        emitter.emit('file-added-to-queue', payload);
    });

    Native.on("App\\Events\\DownloadFinished", (payload) => {
        emitter.emit('download-finished', payload);
    });

    Native.on("App\\Events\\RequestStatusUpdated", (payload) => {
        emitter.emit('request-status-updated', payload);
    });

    Native.on("Native\\Laravel\\Events\\AutoUpdater\\UpdateDownloaded", (payload) => {
        emitter.emit('update-downloaded', payload);
    })
}
