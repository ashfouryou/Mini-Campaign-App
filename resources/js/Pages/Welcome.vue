<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { AcademicCapIcon, ArrowRightIcon, Bars2Icon, Bars4Icon, ChartPieIcon, EnvelopeOpenIcon, GlobeAsiaAustraliaIcon } from '@heroicons/vue/24/solid';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome to Mini Campaign App" />

    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-end">
            <Link
                v-if="$page.props.auth.user"
                :href="route('campaign.index')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm "
                >Dashboard</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm "
                    >Log in</Link
                >

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm "
                    >Register</Link
                >
            </template>
        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex justify-center">
                <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
            </div>

            <div class="mt-8">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white text-center">
                    Welcome to Mini Email Campaign App
                </h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 text-center">
                    Your go-to platform for creating and managing email campaigns with ease. 
                    Upload your contact list, create campaigns, and track their progress effortlessly.
                </p>
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    <a
                        href="#"
                        class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 "
                    >
                        <div>
                            <div
                                class="h-16 w-16 bg-custom-icon-blue-bg flex items-center justify-center rounded-full"
                            >
                            <EnvelopeOpenIcon class="h-10 w-10 text-custom-icon-blue rounded-full" />


                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Create Campaigns</h2>

                            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                Easily create new email campaigns by uploading a CSV file with your contacts. Customize your campaign with a predefined HTML template.
                            </p>
                        </div>

                        <ArrowRightIcon class="self-center shrink-0  w-6 h-6 mx-6" />

                    </a>

                    <a
                        href="#"
                        class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 "
                    >
                        <div>
                            <div
                                class="h-16 w-16 bg-custom-icon-blue-bg flex items-center justify-center rounded-full"
                            >
                              <ChartPieIcon class="h-10 w-10 text-custom-icon-blue rounded-full" />

                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Track Progress</h2>

                            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                Monitor the progress of your campaigns in real-time. View detailed stats on sent, delivered, and opened emails.
                            </p>
                        </div>
                        
                        <ArrowRightIcon class="self-center shrink-0  w-6 h-6 mx-6" />

                    </a>

                    <a
                        href="#"
                        class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 "
                    >
                        <div>
                            <div
                                class="h-16 w-16 bg-custom-icon-blue-bg flex items-center justify-center rounded-full"
                            >
                                  <Bars4Icon class="h-10 w-10 text-custom-icon-blue rounded-full" />
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Manage Multiple Campaigns</h2>

                            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                You can create multiple campaigns and manage them all from a single dashboard. Easily view and edit your campaigns.
                            </p>
                        </div>

                        <ArrowRightIcon class="self-center shrink-0  w-6 h-6 mx-6" />
                    </a>

                    <div
                        class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 "
                    >
                        <div>
                            <div
                                class="h-16 w-16 bg-custom-icon-blue-bg flex items-center justify-center rounded-full"
                            >

                            <GlobeAsiaAustraliaIcon class="h-10 w-10 text-custom-icon-blue rounded-full" />
                  
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Scalable Infrastructure</h2>

                            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                Our app uses Laravel's queue system to process campaigns efficiently and at scale, ensuring your campaigns are delivered on time.
                            </p>
                        </div>
                        <ArrowRightIcon class="self-center shrink-0  w-6 h-6 mx-6" />
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-16 px-6 sm:items-center sm:justify-between">
                <div class="text-center text-sm sm:text-start">&nbsp;</div>

                <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-end sm:ms-0">
                    Designed And Developed By <a href="#" class="text-custom-icon-blue">Ashish Kumar</a> 
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
