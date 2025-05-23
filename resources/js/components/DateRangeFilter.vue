<script setup>
import { Button } from '@/components/ui/button'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { RangeCalendar } from '@/components/ui/range-calendar'
import {
    CalendarDate,
    DateFormatter,
    getLocalTimeZone,
} from '@internationalized/date'
import { Calendar as CalendarIcon } from 'lucide-vue-next'
import { ref, watch } from 'vue'
import { Select, SelectItem, SelectTrigger, SelectValue, SelectContent } from '@/components/ui/select'


defineProps(['isFullWidth'])
const selectValue = ref('all')
const df = new DateFormatter('en-US', {
    dateStyle: 'medium',
})
const emits = defineEmits(['update:range'])
const todayCalenderDate = () => {
    const today = new Date()
    return new CalendarDate(today.getFullYear(), today.getMonth() + 1, today.getDate())
}
const range = ref({
    start: todayCalenderDate(),
    end: todayCalenderDate(),
})


const lastMonthCalenderDate = () => {
    const today = new Date()
    return new CalendarDate(today.getFullYear(), today.getMonth(), 1)
}

const handlePresetChange = (value) => {
    if (!value) return
    selectValue.value = value?.toString()
    const today = todayCalenderDate()
    const lastMonth = lastMonthCalenderDate()
    switch (value) {
        case "today":
            range.value = { start: today, end: today }
            break
        case "last7days":
            range.value = { start: today.subtract({ days: 6 }), end: today }
            break
        case "last30days":
            range.value = { start: today.subtract({ days: 29 }), end: today }
            break
        case "all":
            range.value = { start: todayCalenderDate(), end: todayCalenderDate() }
            break
        case "lastMonth":
            range.value = { start: lastMonth, end: lastMonth.add({ months: 1 }) }
            break
        case "custom":
            // Do nothing, allow manual selection
            range.value.start = todayCalenderDate()
            range.value.end = todayCalenderDate()
            break
    }
}


watch(range, () => {
    if ((range.value?.start != undefined && range.value?.end != undefined)) {
        if (selectValue.value === 'all') {
            emits('update:range', { start: '', end: '' })
            return
        }

        if (selectValue.value === 'today') {
            emits('update:range', {
                start: range.value.start.toDate(getLocalTimeZone()),
                end: new Date(new Date().setHours(23, 59, 59, 999))
            })
            console.log({
                start: range.value.start.toDate(getLocalTimeZone()),
                end: new Date(new Date().setHours(23, 59, 59, 999))
            })
            return
        }
        emits('update:range', { start: range.value.start.toDate(getLocalTimeZone()), end: range.value.end.toDate(getLocalTimeZone()) })
    }
})


</script>

<template>
    <div>
        <Popover>
            <PopoverTrigger as-child>
                <Button variant="outline" class="justify-start text-left font-normal"
                    :class="{ 'w-full': isFullWidth, 'w-[280px]': !isFullWidth, 'text-muted-foreground': !range }">
                    <CalendarIcon class="mr-2 h-4 w-4" />
                    <template v-if="range.start">
                        <template v-if="range.end">
                            {{ df.format(range.start.toDate(getLocalTimeZone())) }} - {{
                                df.format(range.end.toDate(getLocalTimeZone())) }}
                        </template>

                        <template v-else>
                            {{ df.format(range.start.toDate(getLocalTimeZone())) }}
                        </template>
                    </template>
                    <template v-else>
                        Pick a date
                    </template>
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
                <RangeCalendar :disabled="selectValue != 'custom'" v-model="range" initial-focus :number-of-months="2"
                    @update:start-value="(startDate) => range.start = startDate" />
            </PopoverContent>
        </Popover>
    </div>
</template>