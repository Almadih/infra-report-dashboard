<script setup lang="ts">
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Auth } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { AlertTriangle, Building, Camera, CheckCircle, Clock, MapPin, Menu, RouteIcon as Road, Shield, TrendingUp, Wrench, X } from 'lucide-vue-next';
import { useAnimateOnScroll } from '@/composables/useAnimateOnScroll';
import { useAnimatedCounter } from '@/composables/useAnimatedCounter';

type HomePageProps = {
    auth: Auth;
};

defineProps<HomePageProps>();

// --- Refs for animations ---
const heroContent = ref(null);
const heroImage = ref(null);
const problemSection = ref(null);
const howItWorksSection = ref(null);
const featuresSection = ref(null);
const impactSection = ref(null);
const impactCounter1 = ref(null);
const impactCounter2 = ref(null);
const impactCounter3 = ref(null);
const impactCounter4 = ref(null);
const isMenuOpen = ref(false);
// const reportTypesSection = ref(null);
// const ctaSection = ref(null);

// --- Use the composables ---
const { isVisible: heroContentVisible } = useAnimateOnScroll(heroContent);
const { isVisible: heroImageVisible } = useAnimateOnScroll(heroImage);
const { isVisible: problemVisible } = useAnimateOnScroll(problemSection);
const { isVisible: howItWorksVisible } = useAnimateOnScroll(howItWorksSection);
const { isVisible: featuresVisible } = useAnimateOnScroll(featuresSection);
const { isVisible: impactVisible } = useAnimateOnScroll(impactSection);
// const { isVisible: reportTypesVisible } = useAnimateOnScroll(reportTypesSection);
// const { isVisible: ctaVisible } = useAnimateOnScroll(ctaSection);

// --- Animated Counters ---
const animatedRepairTime = useAnimatedCounter(impactCounter1, 50);
const animatedReportIncrease = useAnimatedCounter(impactCounter2, 75);
const animatedCostReduction = useAnimatedCounter(impactCounter3, 30);
const animatedSatisfaction = useAnimatedCounter(impactCounter4, 95);
</script>

<template>

    <Head title="Home">
        <meta name="description" content="Infra report">
    </Head>

    <!-- IMPROVEMENT: Added a background animation container -->
    <div class="flex min-h-screen flex-col bg-gradient-to-b from-slate-50 to-white dark:from-slate-950 dark:to-black">
        <div class="absolute top-0 left-0 w-full h-full bg-white dark:bg-black z-0">
            <div
                class="absolute left-0 right-auto top-50 w-100 h-100 bg-[rgba(16,185,129,0.5)] rounded-full mix-blend-multiply filter blur-[90px] opacity-30 animate-pulse">

            </div>

        </div>

        <header
            class="sticky top-0 z-50 flex h-16 items-center border-b border-slate-200 bg-white/80 px-4 backdrop-blur-sm lg:px-6 dark:border-slate-800 dark:bg-slate-900/80">
            <Link href="/" class="flex items-center justify-center">
            <div class="flex items-center space-x-2">
                <img src="/logo.webp" class="h-12 w-auto" alt="infra report logo" />
            </div>
            </Link>
            <!-- IMPROVEMENT: Added responsive menu for mobile -->
            <nav class="ml-auto hidden md:flex gap-4 sm:gap-6">
                <Link v-if="auth.user" :href="route('dashboard')"
                    class="text-sm font-medium text-slate-700 transition-colors hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                Dashboard
                </Link>
                <template v-else>
                    <Link :href="route('login')"
                        class="text-sm font-medium text-slate-700 transition-colors hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                    Log in
                    </Link>
                </template>
                <a href="#how-it-works"
                    class="text-sm font-medium text-slate-700 transition-colors hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                    How It Works
                </a>
                <a href="#features"
                    class="text-sm font-medium text-slate-700 transition-colors hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                    Features
                </a>
                <a href="#impact"
                    class="text-sm font-medium text-slate-700 transition-colors hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                    Impact
                </a>
                <a href="#download"
                    class="text-sm font-medium text-slate-700 transition-colors hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                    Download
                </a>
            </nav>

            <div class="flex items-center md:hidden ml-auto">
                <Button @click="isMenuOpen = !isMenuOpen" variant="ghost" size="icon" class="rounded-lg">
                    <Menu v-if="!isMenuOpen" class="h-6 w-6" />
                    <X v-else class="h-6 w-6" />
                    <span class="sr-only">Toggle menu</span>
                </Button>
            </div>
            <Transition enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 -translate-y-4" enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition-all duration-200 ease-in" leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-4">
                <div v-if="isMenuOpen"
                    class="absolute top-16 left-0 z-40 w-full border-b border-slate-200 bg-white/95 p-4 backdrop-blur-sm md:hidden dark:border-slate-800 dark:bg-slate-950/95">
                    <nav class="flex flex-col gap-4">
                        <Link v-if="auth.user" :href="route('dashboard')" @click="isMenuOpen = false"
                            class="text-base font-medium text-slate-700 hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                        Dashboard
                        </Link>
                        <template v-else>
                            <Link :href="route('login')" @click="isMenuOpen = false"
                                class="text-base font-medium text-slate-700 hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                            Log in
                            </Link>
                        </template>
                        <a href="#how-it-works" @click="isMenuOpen = false"
                            class="text-base font-medium text-slate-700 hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                            How It Works
                        </a>
                        <a href="#features" @click="isMenuOpen = false"
                            class="text-base font-medium text-slate-700 hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                            Features
                        </a>
                        <a href="#impact" @click="isMenuOpen = false"
                            class="text-base font-medium text-slate-700 hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                            Impact
                        </a>
                        <Button as-child size="lg" @click="isMenuOpen = false"
                            class="w-full bg-emerald-600 text-white hover:bg-emerald-700">
                            <a href="#download">Download Now</a>
                        </Button>
                    </nav>
                </div>
            </Transition>
        </header>



        <main class="flex-1">
            <!-- HERO SECTION -->
            <section class="w-full py-20 md:py-32 lg:py-40 relative">
                <div class=" px-4 md:px-6">
                    <div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
                        <div ref="heroContent"
                            :class="['flex flex-col justify-center space-y-6 transition-all duration-1000', heroContentVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4']">
                            <div class="space-y-4">
                                <Badge variant="secondary"
                                    class="w-fit bg-emerald-100 text-emerald-800 hover:bg-emerald-200 dark:bg-emerald-900/50 dark:text-emerald-300 dark:hover:bg-emerald-800/50 border border-emerald-200 dark:border-emerald-800/60">
                                    Empowering Communities
                                </Badge>
                                <!-- IMPROVEMENT: Gradient text for headline -->
                                <h1
                                    class="text-4xl font-extrabold tracking-tighter sm:text-5xl md:text-6xl lg:text-7xl bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700 dark:from-slate-50 dark:to-slate-300">
                                    Report Infrastructure Issues.
                                    <span class="text-emerald-600 dark:text-emerald-400">Build Safer Communities.</span>
                                </h1>
                                <p class="max-w-[600px] text-slate-600 md:text-xl dark:text-slate-400">
                                    Help your community by reporting potholes, broken bridges, damaged schools, and
                                    other infrastructure issues. Together, we can ensure faster repairs and safer
                                    public spaces for everyone.
                                </p>
                            </div>
                            <div class="flex flex-col gap-3 min-[400px]:flex-row">
                                <!-- IMPROVEMENT: More interactive buttons -->
                                <Button as-child size="lg"
                                    class="bg-emerald-600 text-white hover:bg-emerald-700 transition-transform hover:scale-105 active:scale-95">
                                    <a href="#download">Start Reporting</a>
                                </Button>
                                <Button variant="outline" size="lg"
                                    class="transition-transform hover:scale-105 active:scale-95 dark:border-slate-700 dark:hover:bg-slate-800">
                                    Learn More
                                </Button>
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-slate-600 dark:text-slate-400">
                                <div class="flex items-center space-x-1.5">
                                    <CheckCircle class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                                    <span>Free to use</span>
                                </div>
                                <div class="flex items-center space-x-1.5">
                                    <CheckCircle class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                                    <span>Anonymous reporting</span>
                                </div>
                                <div class="flex items-center space-x-1.5">
                                    <CheckCircle class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                                    <span>Real-time tracking</span>
                                </div>
                            </div>
                        </div>
                        <div ref="heroImage"
                            :class="['flex items-center justify-center transition-all duration-1000 delay-300', heroImageVisible ? 'opacity-100 scale-100' : 'opacity-0 scale-90']">
                            <div class="relative group">
                                <img src="/photo-4.webp" alt="Infrastructure reporting app interface"
                                    class="mx-auto overflow-hidden rounded-xl object-cover shadow-2xl shadow-emerald-900/20 dark:shadow-emerald-400/10" />
                                <!-- IMPROVEMENT: Subtle glow effect on image -->
                                <div
                                    class="absolute -inset-4 bg-emerald-500/10 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- PROBLEM SECTION -->
            <section ref="problemSection"
                :class="['w-full bg-slate-100 py-12 md:py-24 lg:py-32 dark:bg-slate-900/70 transition-all duration-1000', problemVisible ? 'opacity-100' : 'opacity-0']">
                <div class=" px-4 md:px-6">
                    <div class="flex flex-col items-center justify-center space-y-4 text-center">
                        <div class="space-y-2">
                            <h2
                                class="text-3xl font-bold tracking-tighter text-slate-900 sm:text-5xl dark:text-slate-50">
                                The Problem We're Solving
                            </h2>
                            <p
                                class="max-w-[900px] text-slate-600 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed dark:text-slate-400">
                                Infrastructure damage often goes unreported, leading to safety risks and inefficient
                                resource allocation.
                            </p>
                        </div>
                    </div>
                    <!-- IMPROVEMENT: Card hover effects and staggered animation -->
                    <div class="mx-auto grid max-w-5xl items-stretch gap-6 py-12 lg:grid-cols-3 lg:gap-8">
                        <Card
                            :class="['h-full border-0 shadow-lg dark:bg-slate-800/50 transition-all duration-500 hover:!opacity-100 hover:scale-105 hover:shadow-emerald-500/20 group', problemVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5']">
                            <CardHeader class="text-center">
                                <div
                                    class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/30">
                                    <AlertTriangle class="h-6 w-6 text-red-600 dark:text-red-400" />
                                </div>
                                <CardTitle class="text-xl dark:text-slate-100">Unreported Damage</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p class="text-center text-slate-600 dark:text-slate-400">
                                    Critical issues remain unreported, creating safety hazards for entire communities.
                                </p>
                            </CardContent>
                        </Card>
                        <Card
                            :class="['h-full border-0 shadow-lg dark:bg-slate-800/50 transition-all duration-500 delay-150 hover:!opacity-100 hover:scale-105 hover:shadow-emerald-500/20 group', problemVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5']">
                            <CardHeader class="text-center">
                                <div
                                    class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/30">
                                    <Clock class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                                </div>
                                <CardTitle class="text-xl dark:text-slate-100">Delayed Repairs</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p class="text-center text-slate-600 dark:text-slate-400">
                                    Maintenance delays increase costs and put public safety at risk without proper
                                    reporting
                                    channels.
                                </p>
                            </CardContent>
                        </Card>
                        <Card
                            :class="['h-full border-0 shadow-lg dark:bg-slate-800/50 transition-all duration-500 delay-300 hover:!opacity-100 hover:scale-105 hover:shadow-emerald-500/20 group', problemVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5']">
                            <CardHeader class="text-center">
                                <div
                                    class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                                    <TrendingUp class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                                </div>
                                <CardTitle class="text-xl dark:text-slate-100">Resource Waste</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p class="text-center text-slate-600 dark:text-slate-400">
                                    Inefficient allocation leads to higher costs and longer wait times for critical
                                    fixes.
                                </p>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </section>

            <!-- HOW IT WORKS SECTION -->
            <section id="how-it-works" ref="howItWorksSection"
                :class="['w-full py-12 md:py-24 lg:py-32 transition-all duration-1000', howItWorksVisible ? 'opacity-100' : 'opacity-0']">
                <div class="px-4 md:px-6">
                    <div class="flex flex-col items-center justify-center space-y-4 text-center">
                        <div class="space-y-2">
                            <Badge variant="secondary"
                                class="bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">
                                Simple 3-Step Process
                            </Badge>
                            <h2
                                class="text-3xl font-bold tracking-tighter text-slate-900 sm:text-5xl dark:text-slate-50">
                                How It Works
                            </h2>
                            <p
                                class="max-w-[900px] text-slate-600 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed dark:text-slate-400">
                                Reporting infrastructure damage is as easy as taking a photo. Our streamlined process
                                ensures your reports reach the right authorities quickly.
                            </p>
                        </div>
                    </div>
                    <!-- IMPROVEMENT: Added connecting line for better flow -->
                    <div class="relative mx-auto grid max-w-5xl items-start gap-12 py-12 lg:grid-cols-3 lg:gap-10">
                        <!-- Connecting Line -->
                        <div class="absolute top-1/2 left-0 hidden w-full -translate-y-1/2 lg:block">
                            <svg class="w-full" height="2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-dasharray="8 8" stroke-linecap="round" d="M0 1H1000"
                                    class="stroke-slate-300 dark:stroke-slate-700"></path>
                            </svg>
                        </div>
                        <div
                            :class="['relative flex flex-col items-center space-y-4 text-center transition-all duration-500', howItWorksVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5']">
                            <div
                                class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-600 text-2xl font-bold text-white z-10 ring-8 ring-slate-50 dark:ring-slate-950">
                                1</div>
                            <Camera class="h-12 w-12 text-emerald-600 dark:text-emerald-400" />
                            <h3 class="text-xl font-bold dark:text-slate-100">Spot & Capture</h3>
                            <p class="text-slate-600 dark:text-slate-400">
                                Take a photo of the damage. Our app automatically captures location data.
                            </p>
                        </div>
                        <div
                            :class="['relative flex flex-col items-center space-y-4 text-center transition-all duration-500 delay-150', howItWorksVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5']">
                            <div
                                class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-600 text-2xl font-bold text-white z-10 ring-8 ring-slate-50 dark:ring-slate-950">
                                2</div>
                            <MapPin class="h-12 w-12 text-emerald-600 dark:text-emerald-400" />
                            <h3 class="text-xl font-bold dark:text-slate-100">Report & Describe</h3>
                            <p class="text-slate-600 dark:text-slate-400">
                                Add details, select the issue type, and submit your report with precise location data.
                            </p>
                        </div>
                        <div
                            :class="['relative flex flex-col items-center space-y-4 text-center transition-all duration-500 delay-300', howItWorksVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5']">
                            <div
                                class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-600 text-2xl font-bold text-white z-10 ring-8 ring-slate-50 dark:ring-slate-950">
                                3</div>
                            <Wrench class="h-12 w-12 text-emerald-600 dark:text-emerald-400" />
                            <h3 class="text-xl font-bold dark:text-slate-100">Track & Fix</h3>
                            <p class="text-slate-600 dark:text-slate-400">
                                Monitor the status of your report as authorities review and schedule repairs.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FEATURES SECTION -->
            <section id="features" ref="featuresSection"
                :class="['w-full bg-slate-100 py-12 md:py-24 lg:py-32 dark:bg-slate-900/70 transition-all duration-1000', featuresVisible ? 'opacity-100' : 'opacity-0']">
                <!-- Grid background pattern -->
                <div
                    class="absolute inset-0 -z-10 h-full w-full bg-white bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px] dark:bg-slate-950">
                </div>
                <div class="px-4 md:px-6">
                    <div class="flex flex-col items-center justify-center space-y-4 text-center">
                        <div class="space-y-2">
                            <Badge variant="secondary"
                                class="bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">
                                Powerful Features
                            </Badge>
                            <h2
                                class="text-3xl font-bold tracking-tighter text-slate-900 sm:text-5xl dark:text-slate-50">
                                Everything You Need to Make a Difference
                            </h2>
                        </div>
                    </div>
                    <div class="mx-auto grid max-w-5xl items-stretch gap-6 py-12 lg:grid-cols-2 lg:gap-8">
                        <!-- Add card-specific animation here -->
                        <Card
                            class="h-full border bg-white/50 dark:bg-slate-800/50 shadow-md transition-all duration-300 hover:shadow-xl hover:border-emerald-300 dark:hover:border-emerald-700">
                            <CardHeader class="flex flex-row items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/30 shrink-0">
                                    <Camera class="h-6 w-6 text-emerald-600 dark:text-emerald-400" />
                                </div>
                                <CardTitle class="dark:text-slate-100 text-lg">Photo Documentation</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p class="text-slate-600 dark:text-slate-400">
                                    Capture high-quality photos with automatic metadata for comprehensive documentation.
                                </p>
                            </CardContent>
                        </Card>
                        <Card
                            class="h-full border bg-white/50 dark:bg-slate-800/50 shadow-md transition-all duration-300 hover:shadow-xl hover:border-blue-300 dark:hover:border-blue-700">
                            <CardHeader class="flex flex-row items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30 shrink-0">
                                    <MapPin class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                                </div>
                                <CardTitle class="dark:text-slate-100 text-lg">Precise Location Tracking</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p class="text-slate-600 dark:text-slate-400">
                                    Automatic GPS capture ensures authorities can find and address reported issues
                                    quickly.
                                </p>
                            </CardContent>
                        </Card>
                        <Card
                            class="h-full border bg-white/50 dark:bg-slate-800/50 shadow-md transition-all duration-300 hover:shadow-xl hover:border-green-300 dark:hover:border-green-700">
                            <CardHeader class="flex flex-row items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/30 shrink-0">
                                    <Shield class="h-6 w-6 text-green-600 dark:text-green-400" />
                                </div>
                                <CardTitle class="dark:text-slate-100 text-lg">Anonymous Reporting</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p class="text-slate-600 dark:text-slate-400">
                                    Report issues anonymously, ensuring everyone feels safe to contribute to community
                                    safety.
                                </p>
                            </CardContent>
                        </Card>
                        <Card
                            class="h-full border bg-white/50 dark:bg-slate-800/50 shadow-md transition-all duration-300 hover:shadow-xl hover:border-orange-300 dark:hover:border-orange-700">
                            <CardHeader class="flex flex-row items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/30 shrink-0">
                                    <Clock class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                                </div>
                                <CardTitle class="dark:text-slate-100 text-lg">Real-time Status Updates</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p class="text-slate-600 dark:text-slate-400">
                                    Track the progress of your reports from submission to completion, with notifications
                                    at each
                                    stage.
                                </p>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </section>

            <!-- IMPACT SECTION with Animated Counters -->
            <section id="impact" ref="impactSection"
                :class="['w-full py-12 md:py-24 lg:py-32 transition-all duration-1000', impactVisible ? 'opacity-100' : 'opacity-0']">
                <div class=" px-4 md:px-6">
                    <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
                        <div class="flex flex-col justify-center space-y-4">
                            <div class="space-y-2">
                                <Badge variant="secondary"
                                    class="w-fit bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">
                                    Making a Difference
                                </Badge>
                                <h2
                                    class="text-3xl font-bold tracking-tighter text-slate-900 sm:text-5xl dark:text-slate-50">
                                    Real Impact for Real Communities
                                </h2>
                                <p
                                    class="max-w-[600px] text-slate-600 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed dark:text-slate-400">
                                    See how crowdsourced reporting is transforming infrastructure maintenance and
                                    creating safer communities.
                                </p>
                            </div>
                            <div class="grid grid-cols-2 gap-6 py-4">
                                <div ref="impactCounter1" class="space-y-1">
                                    <div class="text-4xl font-bold text-emerald-600 dark:text-emerald-400">{{
                                        animatedRepairTime }}%</div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Faster repair response
                                    </p>
                                </div>
                                <div ref="impactCounter2" class="space-y-1">
                                    <div class="text-4xl font-bold text-emerald-600 dark:text-emerald-400">{{
                                        animatedReportIncrease }}%</div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Increase in reported issues
                                    </p>
                                </div>
                                <div ref="impactCounter3" class="space-y-1">
                                    <div class="text-4xl font-bold text-emerald-600 dark:text-emerald-400">{{
                                        animatedCostReduction }}%</div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Reduction in maintenance costs
                                    </p>
                                </div>
                                <div ref="impactCounter4" class="space-y-1">
                                    <div class="text-4xl font-bold text-emerald-600 dark:text-emerald-400">{{
                                        animatedSatisfaction }}%</div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">User satisfaction rate</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-center">
                            <div class="relative group">
                                <img src="/photo-3.webp" alt="Community impact visualization"
                                    class="mx-auto h-full w-full overflow-hidden rounded-xl object-cover shadow-2xl" />
                                <div
                                    class="absolute -inset-4 bg-emerald-500/10 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="w-full bg-slate-50 py-12 md:py-24 lg:py-32 dark:bg-slate-900">
                <div class="flex-1 px-4 md:px-6">
                    <div class="flex flex-col items-center justify-center space-y-4 text-center">
                        <div class="space-y-2">
                            <h2
                                class="text-3xl font-bold tracking-tighter text-slate-900 sm:text-5xl dark:text-slate-50">
                                What
                                Can You Report?</h2>
                            <p
                                class="max-w-[900px] text-slate-600 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed dark:text-slate-400">
                                Our platform supports reporting of various infrastructure issues that affect community
                                safety
                                and quality of life.
                            </p>
                        </div>
                    </div>
                    <div class="mx-auto grid max-w-5xl items-start gap-6 py-12 lg:grid-cols-3 lg:gap-8">
                        <Card class="border-0 shadow-lg transition-shadow hover:shadow-xl dark:bg-slate-800">
                            <CardHeader class="text-center">
                                <div
                                    class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-800/30">
                                    <Road class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                                </div>
                                <CardTitle class="dark:text-slate-100">Road Infrastructure</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li>• Potholes and road damage</li>
                                    <li>• Broken or missing signage</li>
                                    <li>• Damaged guardrails</li>
                                    <li>• Street lighting issues</li>
                                    <li>• Sidewalk problems</li>
                                </ul>
                            </CardContent>
                        </Card>
                        <Card class="border-0 shadow-lg transition-shadow hover:shadow-xl dark:bg-slate-800">
                            <CardHeader class="text-center">
                                <div
                                    class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-800/30">
                                    <Building class="h-6 w-6 text-green-600 dark:text-green-400" />
                                </div>
                                <CardTitle class="dark:text-slate-100">Public Buildings</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li>• School facility damage</li>
                                    <li>• Hospital infrastructure</li>
                                    <li>• Government buildings</li>
                                    <li>• Public restrooms</li>
                                    <li>• Community centers</li>
                                </ul>
                            </CardContent>
                        </Card>
                        <Card class="border-0 shadow-lg transition-shadow hover:shadow-xl dark:bg-slate-800">
                            <CardHeader class="text-center">
                                <div
                                    class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-800/30">
                                    <AlertTriangle class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                                </div>
                                <CardTitle class="dark:text-slate-100">Critical Infrastructure</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li>• Bridge structural issues</li>
                                    <li>• Water system problems</li>
                                    <li>• Electrical infrastructure</li>
                                    <li>• Public transportation</li>
                                    <li>• Emergency services access</li>
                                </ul>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </section>

            <section class="w-full bg-emerald-600 py-12 md:py-24 lg:py-32 dark:bg-emerald-700" id="download">
                <div class="flex-1 px-4 md:px-6">
                    <div class="flex flex-col items-center justify-center space-y-4 text-center">
                        <div class="space-y-2">
                            <h2 class="text-3xl font-bold tracking-tighter text-white sm:text-5xl">Ready to Make Your
                                Community
                                Safer?</h2>
                            <p
                                class="max-w-[900px] text-emerald-100 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed dark:text-emerald-200">
                                Join thousands of citizens already making a difference. Start reporting infrastructure
                                issues today.
                            </p>
                        </div>
                        <div class="flex flex-col gap-2 min-[400px]:flex-row mt-4">
                            <Button as-child size="lg"
                                class="bg-white text-emerald-700 hover:bg-slate-100 dark:bg-slate-50 dark:text-emerald-700 dark:hover:bg-slate-200 transition-transform hover:scale-105 active:scale-95 shadow-lg">
                                <a href="https://github.com/Almadih/infra-report-app/releases/latest"
                                    target="_blank">Download
                                    the App</a>
                            </Button>
                        </div>
                        <p class="text-sm text-emerald-100 dark:text-emerald-200 pt-2">Available on Android • Free to
                            use •
                            No
                            registration required</p>
                    </div>
                </div>
            </section>
        </main>

        <footer id="contact"
            class="flex w-full shrink-0 flex-col items-center gap-2 border-t border-slate-200 bg-white px-4 py-6 sm:flex-row md:px-6 dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-1 flex-col items-center justify-between md:flex-row w-full">
                <div class="flex items-center space-x-2">
                    <span class="font-bold text-slate-900 dark:text-slate-50">InfraReport</span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400">© 2025 InfraReport. Building safer communities
                    together.
                </p>
                <nav class="flex gap-4 sm:gap-6">
                    <Link href="#"
                        class="text-xs text-slate-600 underline-offset-4 hover:underline dark:text-slate-400 dark:hover:text-slate-200">
                    Privacy Policy
                    </Link>
                    <Link href="#"
                        class="text-xs text-slate-600 underline-offset-4 hover:underline dark:text-slate-400 dark:hover:text-slate-200">
                    Terms of Service
                    </Link>
                </nav>
            </div>
        </footer>
    </div>
</template>