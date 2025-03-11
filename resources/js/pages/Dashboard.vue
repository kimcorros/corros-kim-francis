<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { TimePicker } from '@/components/ui/time-picker';

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

const pageProps = usePage<HomePageProps>().props;
const storeHours = pageProps.storeHours;

const parseTime = (time: string): Date => {
    const [hours, minutes] = time.split(':').map(Number);
    const date = new Date();
    date.setHours(hours, minutes, 0, 0);
    return date;
};

const formatTime = (date: Date): string => {
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
};

const form = useForm({
    openDays: storeHours.open_days,
    openingTime: parseTime(storeHours.opening_time.substring(0, 5)),
    closingTime: parseTime(storeHours.closing_time.substring(0, 5)),
    lunchBreakStart: parseTime(storeHours.lunch_break_start.substring(0, 5)),
    lunchBreakEnd: parseTime(storeHours.lunch_break_end.substring(0, 5)),
    alternateSaturday: storeHours.alternate_saturday,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        openingTime: formatTime(form.openingTime),
        closingTime: formatTime(form.closingTime),
        lunchBreakStart: formatTime(form.lunchBreakStart),
        lunchBreakEnd: formatTime(form.lunchBreakEnd),
    })).post(route('settings.store-hours.update'), {
        preserveScroll: true,
    });
};

const breadcrumbs = [{ title: 'Store Hours', href: '/dashboard' }];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col flex-1 h-full gap-4 p-4 rounded-xl">
            <div>
                <h2 class="mb-12 text-xl font-bold">Update Store Schedule</h2>
                <form @submit.prevent="submit">
                    <div class="flex items-center mb-16 space-x-2">
                        <Checkbox v-model:checked="form.alternateSaturday" id="alternateSaturday" />
                        <Label for="alternateSaturday">Open on alternate Saturdays</Label>
                    </div>
                    <div class="flex mb-6 space-x-4">
                        <div>
                            <Label>Opening Time</Label>
                            <TimePicker :date="form.openingTime" @update:date="(val) => (form.openingTime = val)" />
                        </div>
                        <div>
                            <Label>Closing Time</Label>
                            <TimePicker :date="form.closingTime" @update:date="(val) => (form.closingTime = val)" />
                        </div>
                    </div>
                    <div class="flex mb-12 space-x-4">
                        <div>
                            <Label>Lunch Break Start</Label>
                            <TimePicker :date="form.lunchBreakStart" @update:date="(val) => (form.lunchBreakStart = val)" />
                        </div>
                        <div>
                            <Label>Lunch Break End</Label>
                            <TimePicker :date="form.lunchBreakEnd" @update:date="(val) => (form.lunchBreakEnd = val)" />
                        </div>
                    </div>
                    <div class="mb-12">
                        <Label>Open Days</Label>
                        <div class="grid grid-cols-2 gap-2 mt-6">
                            <label class="flex items-center space-x-2">
                                <Checkbox v-model:checked="form.openDays.monday" />
                                <span>Monday</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <Checkbox v-model:checked="form.openDays.tuesday" />
                                <span>Tuesday</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <Checkbox v-model:checked="form.openDays.wednesday" />
                                <span>Wednesday</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <Checkbox v-model:checked="form.openDays.thursday" />
                                <span>Thursday</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <Checkbox v-model:checked="form.openDays.friday" />
                                <span>Friday</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <Checkbox v-model:checked="form.openDays.saturday" />
                                <span>Saturday</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <Checkbox v-model:checked="form.openDays.sunday" />
                                <span>Sunday</span>
                            </label>
                        </div>
                    </div>
                    <Button type="submit" :disabled="form.processing">Save Schedule</Button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
