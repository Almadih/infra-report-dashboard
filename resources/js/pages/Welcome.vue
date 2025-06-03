<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Auth } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { AlertTriangle, Building, Camera, CheckCircle, Clock, MapPin, RouteIcon as Road, Shield, TrendingUp, Wrench } from 'lucide-vue-next';

type HomePageProps = {
    auth: Auth;
};

defineProps<HomePageProps>();
</script>

<template>

    <Head title="Home">
        <meta name="description" content="Infra report">
    </Head>

    <div class="flex min-h-screen flex-col bg-gradient-to-b from-slate-50 to-white dark:from-slate-950 dark:to-black">
        <header
            class="sticky top-0 z-50 flex h-16 items-center border-b border-slate-200 bg-white/80 px-4 backdrop-blur-sm lg:px-6 dark:border-slate-800 dark:bg-slate-900/80">
            <Link href="/" class="flex items-center justify-center">
            <div class="flex items-center space-x-2">
                <img src="logo.webp" class="h-12 w-auto" alt="infra report logo" />
            </div>
            </Link>
            <nav class="ml-auto flex gap-4 sm:gap-6">
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
                <Link href="#features"
                    class="text-sm font-medium text-slate-700 transition-colors hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                Features
                </Link>
                <Link href="#how-it-works"
                    class="text-sm font-medium text-slate-700 transition-colors hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                How It Works
                </Link>
                <Link href="#impact"
                    class="text-sm font-medium text-slate-700 transition-colors hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                Impact
                </Link>
                <Link href="#contact"
                    class="text-sm font-medium text-slate-700 transition-colors hover:text-emerald-600 dark:text-slate-300 dark:hover:text-emerald-400">
                Contact
                </Link>
            </nav>
        </header>

        <main class="flex-1">
            <section class="w-full py-12 md:py-24 lg:py-32">
                <div class="flex-1 px-4 md:px-6">
                    <div class="grid gap-6 lg:grid-cols-[1fr_400px] lg:gap-12 xl:grid-cols-[1fr_600px]">
                        <div class="flex flex-col justify-center space-y-4">
                            <div class="space-y-2">
                                <Badge variant="secondary"
                                    class="w-fit bg-emerald-100 text-emerald-800 hover:bg-emerald-200 dark:bg-emerald-800 dark:text-emerald-200 dark:hover:bg-emerald-700">
                                    Empowering Communities
                                </Badge>
                                <h1
                                    class="text-3xl font-bold tracking-tighter text-slate-900 sm:text-5xl xl:text-6xl/none dark:text-slate-50">
                                    Report Infrastructure Issues. <span
                                        class="text-emerald-600 dark:text-emerald-400">Build
                                        Safer Communities.</span>
                                </h1>
                                <p class="max-w-[600px] text-slate-600 md:text-xl dark:text-slate-400">
                                    Help your community by reporting potholes, broken bridges, damaged schools, and
                                    other
                                    infrastructure issues.
                                    Together, we can ensure faster repairs and safer public spaces for everyone.
                                </p>
                            </div>
                            <div class="flex flex-col gap-2 min-[400px]:flex-row">
                                <Button size="lg" class="cursor-pointer bg-emerald-600 text-white hover:bg-emerald-700">
                                    Start
                                    Reporting </Button>
                                <Button variant="outline" size="lg"
                                    class="cursor-pointer dark:border-slate-600 dark:text-slate-50 dark:hover:bg-slate-800">
                                    Learn More
                                </Button>
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-slate-600 dark:text-slate-400">
                                <div class="flex items-center space-x-1">
                                    <CheckCircle class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                                    <span>Free to use</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <CheckCircle class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                                    <span>Anonymous reporting</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <CheckCircle class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                                    <span>Real-time tracking</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-center">
                            <div class="relative">
                                <img src="photo-4.webp" alt="Infrastructure reporting app interface"
                                    class="mx-auto h-[100%] w-[100%] overflow-hidden rounded-xl object-cover shadow-2xl" />
                                <div
                                    class="absolute inset-0 rounded-xl bg-gradient-to-t from-black/20 to-transparent dark:from-black/40" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="w-full bg-slate-100 py-12 md:py-24 lg:py-32 dark:bg-slate-900">
                <div class="flex-1 px-4 md:px-6">
                    <div class="flex flex-col items-center justify-center space-y-4 text-center">
                        <div class="space-y-2">
                            <h2
                                class="text-3xl font-bold tracking-tighter text-slate-900 sm:text-5xl dark:text-slate-50">
                                The Problem We're Solving
                            </h2>
                            <p
                                class="max-w-[900px] text-slate-600 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed dark:text-slate-400">
                                Infrastructure damage often goes unreported, leading to safety risks and inefficient
                                resource
                                allocation. Traditional
                                reporting channels are inaccessible or underutilized by communities that need them most.
                            </p>
                        </div>
                    </div>
                    <div class="mx-auto grid max-w-5xl items-center gap-6 py-12 lg:grid-cols-3 lg:gap-12">
                        <Card class="h-full border-0 shadow-lg dark:bg-slate-800">
                            <CardHeader class="text-center">
                                <div
                                    class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 dark:bg-red-800/30">
                                    <AlertTriangle class="h-6 w-6 text-red-600 dark:text-red-400" />
                                </div>
                                <CardTitle class="text-xl dark:text-slate-100">Unreported Damage</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p class="text-center text-slate-600 dark:text-slate-400">
                                    Critical infrastructure issues like potholes and broken bridges remain unreported,
                                    creating
                                    safety hazards for
                                    entire communities.
                                </p>
                            </CardContent>
                        </Card>
                        <Card class="h-full border-0 shadow-lg dark:bg-slate-800">
                            <CardHeader class="text-center">
                                <div
                                    class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-800/30">
                                    <Clock class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                                </div>
                                <CardTitle class="text-xl dark:text-slate-100">Delayed Repairs</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p class="text-center text-slate-600 dark:text-slate-400">
                                    Without proper reporting channels, maintenance delays increase costs and put public
                                    safety
                                    at risk.
                                </p>
                            </CardContent>
                        </Card>
                        <Card class="h-full border-0 shadow-lg dark:bg-slate-800">
                            <CardHeader class="text-center">
                                <div
                                    class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-800/30">
                                    <TrendingUp class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                                </div>
                                <CardTitle class="text-xl dark:text-slate-100">Resource Waste</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p class="text-center text-slate-600 dark:text-slate-400">
                                    Inefficient allocation of repair resources leads to higher costs and longer wait
                                    times for
                                    critical fixes.
                                </p>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </section>

            <section id="how-it-works" class="w-full py-12 md:py-24 lg:py-32">
                <div class="flex-1 px-4 md:px-6">
                    <div class="flex flex-col items-center justify-center space-y-4 text-center">
                        <div class="space-y-2">
                            <Badge variant="secondary"
                                class="bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-200">
                                Simple Process
                            </Badge>
                            <h2
                                class="text-3xl font-bold tracking-tighter text-slate-900 sm:text-5xl dark:text-slate-50">
                                How It
                                Works</h2>
                            <p
                                class="max-w-[900px] text-slate-600 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed dark:text-slate-400">
                                Reporting infrastructure damage is as easy as taking a photo. Our streamlined process
                                ensures
                                your reports reach the
                                right authorities quickly.
                            </p>
                        </div>
                    </div>
                    <div class="mx-auto grid max-w-5xl items-start gap-6 py-12 lg:grid-cols-3 lg:gap-12">
                        <div class="flex flex-col items-center space-y-4 text-center">
                            <div
                                class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-600 text-xl font-bold text-white">
                                1</div>
                            <Camera class="h-12 w-12 text-emerald-600 dark:text-emerald-400" />
                            <h3 class="text-xl font-bold dark:text-slate-100">Spot & Capture</h3>
                            <p class="text-slate-600 dark:text-slate-400">
                                Take a photo of the infrastructure damage you've discovered. Our app automatically
                                captures
                                location data.
                            </p>
                        </div>
                        <div class="flex flex-col items-center space-y-4 text-center">
                            <div
                                class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-600 text-xl font-bold text-white">
                                2</div>
                            <MapPin class="h-12 w-12 text-emerald-600 dark:text-emerald-400" />
                            <h3 class="text-xl font-bold dark:text-slate-100">Report & Describe</h3>
                            <p class="text-slate-600 dark:text-slate-400">
                                Add details about the damage, select the issue type, and submit your report with precise
                                location data.
                            </p>
                        </div>
                        <div class="flex flex-col items-center space-y-4 text-center">
                            <div
                                class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-600 text-xl font-bold text-white">
                                3</div>
                            <Wrench class="h-12 w-12 text-emerald-600 dark:text-emerald-400" />
                            <h3 class="text-xl font-bold dark:text-slate-100">Track & Fix</h3>
                            <p class="text-slate-600 dark:text-slate-400">
                                Monitor the status of your report as authorities review and schedule repairs. Get
                                notified when
                                work begins.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="features" class="w-full bg-slate-50 py-12 md:py-24 lg:py-32 dark:bg-slate-900">
                <div class="flex-1 px-4 md:px-6">
                    <div class="flex flex-col items-center justify-center space-y-4 text-center">
                        <div class="space-y-2">
                            <Badge variant="secondary"
                                class="bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-200">
                                Powerful Features
                            </Badge>
                            <h2
                                class="text-3xl font-bold tracking-tighter text-slate-900 sm:text-5xl dark:text-slate-50">
                                Everything You Need to Make a Difference
                            </h2>
                            <p
                                class="max-w-[900px] text-slate-600 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed dark:text-slate-400">
                                Our platform provides all the tools needed for effective infrastructure damage reporting
                                and
                                tracking.
                            </p>
                        </div>
                    </div>
                    <div class="mx-auto grid max-w-5xl items-start gap-6 py-12 lg:grid-cols-2 lg:gap-12">
                        <Card class="h-full border-0 shadow-lg dark:bg-slate-800">
                            <CardHeader>
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-800/30">
                                        <Camera class="h-5 w-5 text-emerald-600 dark:text-emerald-400" />
                                    </div>
                                    <CardTitle class="dark:text-slate-100">Photo Documentation</CardTitle>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <p class="text-slate-600 dark:text-slate-400">
                                    Capture high-quality photos with automatic metadata including timestamp, GPS
                                    coordinates,
                                    and device information
                                    for comprehensive documentation.
                                </p>
                            </CardContent>
                        </Card>
                        <Card class="h-full border-0 shadow-lg dark:bg-slate-800">
                            <CardHeader>
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-800/30">
                                        <MapPin class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                                    </div>
                                    <CardTitle class="dark:text-slate-100">Precise Location Tracking</CardTitle>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <p class="text-slate-600 dark:text-slate-400">
                                    Automatic GPS location capture ensures authorities can find and address reported
                                    issues
                                    quickly and efficiently.
                                </p>
                            </CardContent>
                        </Card>

                        <Card class="h-full border-0 shadow-lg dark:bg-slate-800">
                            <CardHeader>
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-800/30">
                                        <Shield class="h-5 w-5 text-green-600 dark:text-green-400" />
                                    </div>
                                    <CardTitle class="dark:text-slate-100">Anonymous Reporting</CardTitle>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <p class="text-slate-600 dark:text-slate-400">
                                    Report issues anonymously if preferred, ensuring everyone feels safe to contribute
                                    to
                                    community safety and
                                    infrastructure improvement.
                                </p>
                            </CardContent>
                        </Card>
                        <Card class="h-full border-0 shadow-lg dark:bg-slate-800">
                            <CardHeader>
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-800/30">
                                        <Clock class="h-5 w-5 text-orange-600 dark:text-orange-400" />
                                    </div>
                                    <CardTitle class="dark:text-slate-100">Real-time Status Updates</CardTitle>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <p class="text-slate-600 dark:text-slate-400">
                                    Track the progress of your reports from submission to completion, with notifications
                                    at each
                                    stage of the repair
                                    process.
                                </p>
                            </CardContent>
                        </Card>
                        <Card class="h-full border-0 shadow-lg dark:bg-slate-800">
                            <CardHeader>
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 dark:bg-red-800/30">
                                        <AlertTriangle class="h-5 w-5 text-red-600 dark:text-red-400" />
                                    </div>
                                    <CardTitle class="dark:text-slate-100">Priority Classification</CardTitle>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <p class="text-slate-600 dark:text-slate-400">
                                    Severity assessment helps authorities prioritize urgent safety issues while ensuring
                                    all
                                    reports receive
                                    appropriate attention.
                                </p>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </section>

            <section id="impact" class="w-full py-12 md:py-24 lg:py-32">
                <div class="flex-1 px-4 md:px-6">
                    <div class="grid items-center gap-6 lg:grid-cols-[1fr_400px] lg:gap-12 xl:grid-cols-[1fr_600px]">
                        <div class="flex flex-col justify-center space-y-4">
                            <div class="space-y-2">
                                <Badge variant="secondary"
                                    class="w-fit bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-200">
                                    Making a Difference
                                </Badge>
                                <h2
                                    class="text-3xl font-bold tracking-tighter text-slate-900 sm:text-5xl dark:text-slate-50">
                                    Real Impact for Real Communities
                                </h2>
                                <p
                                    class="max-w-[600px] text-slate-600 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed dark:text-slate-400">
                                    See how crowdsourced reporting is transforming infrastructure maintenance and
                                    creating safer
                                    communities
                                    worldwide.
                                </p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 py-4">
                                <div class="space-y-2">
                                    <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">50%</div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Faster repair response times
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">75%</div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Increase in reported issues
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">30%</div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Reduction in maintenance costs
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">95%</div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">User satisfaction rate</p>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2 min-[400px]:flex-row">
                                <Button class="bg-emerald-600 text-white hover:bg-emerald-700">Join the
                                    Movement</Button>
                                <Button variant="outline"
                                    class="dark:border-slate-600 dark:text-slate-50 dark:hover:bg-slate-800">View Case
                                    Studies</Button>
                            </div>
                        </div>
                        <div class="flex items-center justify-center">
                            <div class="relative">
                                <img src="photo-3.webp" alt="Community impact visualization"
                                    class="mx-auto h-[100%] w-[100%] overflow-hidden rounded-xl object-cover shadow-2xl" />
                                <div
                                    class="absolute inset-0 rounded-xl bg-gradient-to-t from-black/20 to-transparent dark:from-black/40" />
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

            <section class="w-full bg-emerald-600 py-12 md:py-24 lg:py-32 dark:bg-emerald-700">
                <div class="flex-1 px-4 md:px-6">
                    <div class="flex flex-col items-center justify-center space-y-4 text-center">
                        <div class="space-y-2">
                            <h2 class="text-3xl font-bold tracking-tighter text-white sm:text-5xl">Ready to Make Your
                                Community
                                Safer?</h2>
                            <p
                                class="max-w-[900px] text-emerald-100 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed dark:text-emerald-200">
                                Join thousands of citizens already making a difference. Start reporting infrastructure
                                issues
                                today and help build
                                stronger, safer communities for everyone.
                            </p>
                        </div>
                        <div class="flex flex-col gap-2 min-[400px]:flex-row">
                            <Button size="lg"
                                class="cursor-pointer bg-white text-emerald-600 hover:bg-slate-100 dark:bg-slate-50 dark:text-emerald-700 dark:hover:bg-slate-200">
                                Download the App
                            </Button>
                            <Button size="lg"
                                class="cursor-pointer bg-white text-emerald-600 hover:bg-slate-100 dark:bg-slate-50 dark:text-emerald-700 dark:hover:bg-slate-200">
                                Learn More
                            </Button>
                        </div>
                        <p class="text-sm text-emerald-100 dark:text-emerald-200">Available on Android • Free to use •
                            No
                            registration required</p>
                    </div>
                </div>
            </section>
        </main>

        <footer id="contact"
            class="flex w-full shrink-0 flex-col items-center gap-2 border-t border-slate-200 bg-white px-4 py-6 sm:flex-row md:px-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-1 flex-col items-center justify-between md:flex-row">
                <div class="flex items-center space-x-2">
                    <div class="flex h-6 w-6 items-center justify-center rounded bg-emerald-600">
                        <AlertTriangle class="h-4 w-4 text-white" />
                    </div>
                    <span class="font-bold text-slate-900 dark:text-slate-50">InfraReport</span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400">© 2024 InfraReport. Building safer communities
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
                    <Link href="#"
                        class="text-xs text-slate-600 underline-offset-4 hover:underline dark:text-slate-400 dark:hover:text-slate-200">
                    Contact Us
                    </Link>
                </nav>
            </div>
        </footer>
    </div>
</template>
