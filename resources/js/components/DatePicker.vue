<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { cn } from '@/lib/utils';
import { DateFormatter, type DateValue, getLocalTimeZone } from '@internationalized/date';
import { CalendarIcon } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const df = new DateFormatter('en-US', { dateStyle: 'long' });

const props = defineProps<{ modelValue?: DateValue | null }>();
const emits = defineEmits<{ (e: 'update:modelValue', value: DateValue | null): void }>();

const internalValue = ref<DateValue | null>(props.modelValue ?? null);

watch(
    () => props.modelValue,
    (newVal) => {
        internalValue.value = newVal ?? null;
    },
);

watch(internalValue, (newVal) => {
    console.log('DatePicker internal value changed:', newVal);
    emits('update:modelValue', newVal);
});

const formatted = computed(() => (internalValue.value ? df.format(internalValue.value.toDate(getLocalTimeZone())) : 'Pick a date'));
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button variant="outline" :class="cn('w-[280px] justify-start text-left font-normal', !internalValue ? 'text-muted-foreground' : '')">
                <CalendarIcon class="mr-2 h-4 w-4" />
                <span>{{ formatted }}</span>
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0">
            <Calendar v-model="internalValue" initial-focus />
        </PopoverContent>
    </Popover>
</template>
