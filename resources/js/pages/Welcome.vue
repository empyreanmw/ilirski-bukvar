<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Ilirski Bukvar">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="flex min-h-screen flex-col bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
        <!-- Hero -->
        <main class="flex-1 flex flex-col items-center justify-center px-6 text-center lg:px-20">
            <img src="/images/logo.png" alt="Ilirski Bukvar Logo" class="dark:hidden w-22 h-22 mb-4" />
            <img src="/images/logo-dark.png" alt="Ilirski Bukvar Logo" class="hidden dark:block w-22 h-22 mb-4" />

            <h1 class="text-3xl font-semibold mb-3">Ilirski Bukvar</h1>

            <p class="mb-20 max-w-xl text-base text-[#4a4a46] dark:text-[#A1A09A] leading-relaxed">
                Dobrodošao u Ilirski Bukvar — digitalnu zbirku znanja, vere i zdravlja. Ova aplikacija ti omogućava da otkriješ duboko ukorenjene vrednosti kroz istoriju Ilira, Sveto pismo i principe prirodnog života. Pristupi mudrosti prošlosti i primeni je danas.
            </p>

            <!-- Thematic Areas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl w-full mb-10 px-4">
                <div class="rounded-lg border border-[#ececeb] dark:border-[#2a2a29] p-5">
                    <h3 class="text-sm font-semibold mb-2">📖 Istorija Ilira</h3>
                    <p class="text-xs text-[#6a6a6a] dark:text-[#999] leading-relaxed">
                        Istraži poreklo slovenskih i ilirskih naroda kroz tekstove, hronike i autentična tumačenja. Uči o jeziku, pismu i duhovnoj kulturi zaboravljenih vremena.
                    </p>
                </div>

                <div class="rounded-lg border border-[#ececeb] dark:border-[#2a2a29] p-5">
                    <h3 class="text-sm font-semibold mb-2">✝️ Sveto Pismo i teologija</h3>
                    <p class="text-xs text-[#6a6a6a] dark:text-[#999] leading-relaxed">
                        Upoznaj se sa Biblijom i hrišćanskim učenjima kroz tekstove, objašnjenja i komentare. Učenje vere na jednostavan i pristupačan način.
                    </p>
                </div>

                <div class="rounded-lg border border-[#ececeb] dark:border-[#2a2a29] p-5">
                    <h3 class="text-sm font-semibold mb-2">🍎 Zdrav život</h3>
                    <p class="text-xs text-[#6a6a6a] dark:text-[#999] leading-relaxed">
                        Saveti o ishrani, zdravlju, postu i prirodnim metodama lečenja. Spoj drevne mudrosti i modernih znanja za telo i duh.
                    </p>
                </div>

                <div class="rounded-lg border border-[#ececeb] dark:border-[#2a2a29] p-5">
                    <h3 class="text-sm font-semibold mb-2">💾 Preuzimanje sadržaja</h3>
                    <p class="text-xs text-[#6a6a6a] dark:text-[#999] leading-relaxed">
                        Svi video i tekstualni materijali dostupni su za preuzimanje — klikom na dugme sačuvaj omiljeni sadržaj direktno na svoj računar.
                    </p>
                </div>
            </div>


            <!-- CTA Button -->
            <div class="mb-2">
                <Link
                    :href="route('history')"
                    class="inline-block rounded-md bg-[#1b1b18] px-6 py-2 text-white text-sm font-medium shadow hover:bg-[#333] dark:bg-[#EDEDEC] dark:text-[#1C1C1A] dark:hover:bg-white"
                >
                    {{'Uđi u aplikaciju'}}
                </Link>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full border-t border-[#e5e5e5] dark:border-[#2a2a29] p-4 text-center text-xs text-[#888] dark:text-[#777]">
            Verzija 1.0 • Ilirski Bukvar © {{ new Date().getFullYear() }}
        </footer>
    </div>
</template>
