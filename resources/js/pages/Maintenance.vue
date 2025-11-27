<script setup lang="ts">
import { Head, Link, usePage, router } from '@inertiajs/vue3';
const page = usePage();
const reload = () => {
    router.visit(route('history'))
};
</script>

<template>
    <Head title="Održavanje | Ilirski Bukvar">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="flex min-h-screen flex-col bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
        <!-- Main -->
        <main class="flex-1 flex flex-col items-center justify-center px-6 text-center lg:px-20">
            <img src="/images/logo.png" alt="Ilirski Bukvar Logo" class="dark:hidden w-22 h-22 mb-4" />
            <img src="/images/logo-dark.png" alt="Ilirski Bukvar Logo" class="hidden dark:block w-22 h-22 mb-4" />

            <h1 class="text-3xl font-semibold mb-3">
                U toku je održavanje
            </h1>

            <p class="mb-6 max-w-md text-base text-[#4a4a46] dark:text-[#A1A09A] leading-relaxed">
                Aplikacija je trenutno nedostupna zbog radova na sistemu.
                Ako je aplikacija nedostupna duze vreme, kontaktirajte nas na kontakt@ilirski-bukvar.rs
            </p>

            <p class="mb-10 max-w-md text-sm text-[#6a6a6a] dark:text-[#999] leading-relaxed">
                Hvala na strpljenju i razumevanju.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 items-center justify-center">
                <button
                    type="button"
                    @click="reload"
                    class="inline-block rounded-md bg-[#1b1b18] px-6 py-2 text-white text-sm font-medium shadow hover:bg-[#333] dark:bg-[#EDEDEC] dark:text-[#1C1C1A] dark:hover:bg-white"
                >
                    Pokušaj ponovo
                </button>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full border-t border-[#e5e5e5] dark:border-[#2a2a29] p-4 text-center text-xs text-[#888] dark:text-[#777]">
            {{ page.props.version }} • Ilirski Bukvar © {{ new Date().getFullYear() }}
        </footer>
    </div>
</template>
