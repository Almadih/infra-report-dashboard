<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { RangeCalendar } from '@/components/ui/range-calendar';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { CalendarDate, DateFormatter, getLocalTimeZone, type DateValue } from '@internationalized/date';
import { Calendar as CalendarIcon } from 'lucide-vue-next';
import { DateRange } from 'reka-ui'; // This is { start: CalendarDate, end: CalendarDate }
import type { Ref } from 'vue';
import { ref, shallowRef, watch } from 'vue';

interface Props {
    isFullWidth?: boolean;
}
defineProps<Props>();
const selectValue: Ref<string> = ref('all');
const df = new DateFormatter('en-US', {
    dateStyle: 'medium',
});
const emits = defineEmits<{
    (e: 'update:range', payload: { start: string; end: string }): void;
}>();
const todayCalenderDate = () => {
    const today = new Date();
    return new CalendarDate(today.getFullYear(), today.getMonth() + 1, today.getDate());
};

const lastMonthCalenderDate = () => {
    const today = new Date();
    return new CalendarDate(today.getFullYear(), today.getMonth(), 1);
};

const range = shallowRef<DateRange>({
    start: todayCalenderDate() as DateValue,
    end: todayCalenderDate() as DateValue,
});


const onRangeUpdateFromCalendar = (newDateRange: any) => {
    range.value = {
        start: newDateRange.start as DateValue,
        end: newDateRange.end as DateValue,
    };
};

const handlePresetChange = (value: any) => {
    if (!value) return;
    selectValue.value = value.toString();
    const today = todayCalenderDate();
    const lastMonth = lastMonthCalenderDate();
    switch (value) {
        case 'today':
            range.value = { start: today, end: today };
            break;
        case 'last7days':
            range.value = { start: today.subtract({ days: 6 }), end: today };
            break;
        case 'last30days':
            range.value = { start: today.subtract({ days: 29 }), end: today };
            break;
        case 'all':
            range.value = { start: todayCalenderDate(), end: todayCalenderDate() };
            break;
        case 'lastMonth':
            range.value = { start: lastMonth, end: lastMonth.add({ months: 1 }).subtract({ days: 1 }) }; // Corrected: end of last month
            break;
        case 'custom':
            range.value = { start: todayCalenderDate(), end: todayCalenderDate() };
            break;
    }
};

watch(range, () => {
    if (range.value?.start != undefined && range.value?.end != undefined) {
        if (selectValue.value === 'all') {
            emits('update:range', { start: '', end: '' });
            return;
        }

        if (selectValue.value === 'today') {
            emits('update:range', {
                start: range.value.start.toDate(getLocalTimeZone()).toISOString(),
                end: new Date(new Date(range.value.start.toDate(getLocalTimeZone())).setHours(23, 59, 59, 999)).toISOString(), // Ensure end of day for 'today'
            });
            return;
        }
        emits('update:range', { start: range.value.start.toDate(getLocalTimeZone()).toISOString(), end: range.value.end.toDate(getLocalTimeZone()).toISOString() });
    }
});
</script>

<template>
    <div>
        <Popover>
            <PopoverTrigger as-child>
                <Button variant="outline" class="justify-start text-left font-normal"
                    :class="{ 'w-full': isFullWidth, 'w-[280px]': !isFullWidth, 'text-muted-foreground': !range.start }">

                    <CalendarIcon class="mr-2 h-4 w-4" />
                    <template v-if="range.start">
                        <template v-if="range.end && range.start.compare(range.end) !== 0">
                            {{ df.format(range.start.toDate(getLocalTimeZone())) }} - {{
                                df.format(range.end.toDate(getLocalTimeZone())) }}
                        </template>

                        <template v-else>
                            {{ df.format(range.start.toDate(getLocalTimeZone())) }}
                        </template>
                    </template>
                    <template v-else> Pick a date </template>
                </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
                <Select :model-value="selectValue" @update:model-value="handlePresetChange">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Select a preset" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="all">All Time</SelectItem>
                        <SelectItem value="today">Today</SelectItem>
                        <SelectItem value="last7days">Last 7 days</SelectItem>
                        <SelectItem value="last30days">Last 30 days</SelectItem>
                        <SelectItem value="lastMonth">Last Month</SelectItem>
                        <SelectItem value="custom">Custom</SelectItem>
                    </SelectContent>
                </Select>
                <RangeCalendar :disabled="selectValue != 'custom'" :model-value="range"
                    @update:model-value="onRangeUpdateFromCalendar" initial-focus :number-of-months="2" />
            </PopoverContent>
        </Popover>
    </div>
</template>