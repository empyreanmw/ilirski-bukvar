// composables/useContentHelpers.ts
import { usePage } from '@inertiajs/vue3';
import { AppMode, AppModeSwitch } from '@/types';
import { PageProps } from '@inertiajs/core';

export function useAppMode() {
    const page:Page<PageProps> = usePage();
    const isAppOnline = (): boolean => page.props.appMode.mode === 'online'
    const isAppModeSwitchManual = (): boolean => page.props.appMode.switch as AppModeSwitch === 'manual'
    const getAppMode = (): AppMode => page.props.appMode.mode

    return {
        isAppOnline,
        isAppModeSwitchManual,
        getAppMode
    }
}
