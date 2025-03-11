<script setup lang="ts">
import DatePicker from '@/components/DatePicker.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { type DateValue } from '@internationalized/date';
import { computed, ref } from 'vue';

interface HomePageProps extends Record<string, any> {
    storeHours?: {
        open_days: Record<string, boolean>;
        opening_time: string;
        closing_time: string;
        lunch_break_start: string;
        lunch_break_end: string;
        alternate_saturday: boolean;
    };
}

const page = usePage<HomePageProps>();
const storeHours = computed(() => page.props?.storeHours);

function timeStringToMinutes(timeStr: string): number {
    const [h, m] = timeStr.substring(0, 5).split(':').map(Number);
    return h * 60 + m;
}

function isStoreOpen(date: Date): boolean {
    const day = date.toLocaleDateString('en-US', { weekday: 'long' });
    const minutesNow = date.getHours() * 60 + date.getMinutes();

    console.log(storeHours.value?.open_days[day.toLowerCase()]);

    if (!storeHours.value?.open_days[day.toLowerCase()]) {
        if (day === 'Saturday' && storeHours.value?.alternate_saturday) {
            const oneJan = new Date(date.getFullYear(), 0, 1);
            const weekNumber = Math.ceil(((date.getTime() - oneJan.getTime()) / 86400000 + oneJan.getDay() + 1) / 7);

            console.log(weekNumber);
            if (weekNumber % 2 === 0) return false;
        } else {
            return false;
        }
    }

    const openTime = timeStringToMinutes(storeHours.value.opening_time);
    const closeTime = timeStringToMinutes(storeHours.value.closing_time);
    const lunchStart = timeStringToMinutes(storeHours.value.lunch_break_start);
    const lunchEnd = timeStringToMinutes(storeHours.value.lunch_break_end);

    if (minutesNow < openTime || minutesNow >= closeTime) return false;
    if (minutesNow >= lunchStart && minutesNow < lunchEnd) return false;
    return true;
}

const storeStatus = computed(() => (isStoreOpen(new Date()) ? 'Open' : 'Closed'));

function getNextOpeningDate(): Date {
    const date = new Date();
    while (true) {
        date.setDate(date.getDate() + 1);
        if (isStoreOpen(date)) return date;
    }
}

const nextOpening = computed(() => {
    if (storeStatus.value === 'Closed') {
        const nextDate = getNextOpeningDate();
        const diffMs = nextDate.getTime() - new Date().getTime();
        const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
        if (diffDays > 0) return `${diffDays} day(s) from now`;
        const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
        return `${diffHours} hour(s) from now`;
    }
    return '';
});

const selectedDate = ref<DateValue | null>(null);

function checkDateStatus(date: string | null): string {
    if (date) {
        const selected = new Date(date);
        const open = isStoreOpen(selected);
        return open ? 'The bakery is open on that day.' : `The bakery is closed on that day. Next opening in: ${nextOpening.value}`;
    }
    return '';
}
</script>

<template>
    <Head title="Home" />
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>
        <div class="duration-750 flex w-full items-center justify-center transition-opacity lg:grow">
            <main class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg bg-white dark:bg-[#161615] lg:max-w-4xl lg:flex-row">
                <div class="flex-1 p-6 lg:p-10">
                    <h1 class="mb-2 text-2xl font-bold">Welcome to Amy and Dave's Bakery</h1>
                    <p class="mb-4">
                        Our store is currently: <strong>{{ storeStatus }}</strong>
                    </p>
                    <p v-if="storeStatus === 'Closed'" class="mb-4 text-sm text-gray-600">
                        Next opening in: <strong>{{ nextOpening }}</strong>
                    </p>
                </div>
                <div class="flex-1 border-t p-6 lg:border-l lg:border-t-0 lg:p-10">
                    <h2 class="mb-2 text-xl font-semibold">Check if we’re open on a specific day</h2>
                    <DatePicker v-model="selectedDate" />
                    <p class="mt-2">
                        {{ selectedDate ? checkDateStatus(selectedDate) : 'Please pick a date.' }}
                    </p>
                </div>
            </main>
        </div>
        <div class="h-14.5 hidden lg:block"></div>
    </div>
</template>
