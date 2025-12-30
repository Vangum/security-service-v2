<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem} from '@/types';
import {Head, router, useForm} from '@inertiajs/vue3';
import {visitorsCreate, visitorsIndex, visitorsUpdate} from "@/routes";
import {Button} from '@/components/ui/button'
import {RadioGroup, RadioGroupItem,} from '@/components/ui/radio-group'
import {Field, FieldDescription, FieldError, FieldGroup, FieldLabel, FieldLegend, FieldSet,} from '@/components/ui/field'
import {Input} from '@/components/ui/input'
import {Select, SelectContent, SelectItem, SelectTrigger, SelectValue,} from '@/components/ui/select'
import {Textarea} from '@/components/ui/textarea'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Редактировать посетителя',
        href: visitorsCreate().url,
    },
];

interface Department {
    id: number;
    name: string;
}

interface Document {
    type: string;
    passport_series_for_input?: string;
    passport_number?: string;
    passport_issue_date?: string;
    passport_issued_by?: string;
    passport_department_code?: string;
    
    license_series_number_for_input?: string;
    license_issue_date?: string;
    license_region?: string;
    license_issued_by?: string;
    
    other_document_name?: string;
    other_series_number_original?: string;
    other_issue_date?: string;
    other_issued_by?: string;
}

interface Visitor {
    id: number,
    full_name: string;
    department: Department;
    birth_date_for_input: string;
    position: string;
    document: Document;
    phone: string;
    entry_datetime_for_input: string;
    exit_datetime_for_input: string;
    remarks: string | null;
}

const {departments, visitor} = defineProps<{
    departments: Department[];
    visitor: Visitor;
}>();

const form = useForm({
    full_name: visitor.full_name,
    department: visitor.department.id,
    birth_date: visitor.birth_date_for_input,
    position: visitor.position,
    phone: visitor.phone,
    
    document_type: visitor.document.type,
    
    passport_series: visitor.document.passport_series_for_input ?? '',
    passport_number: visitor.document.passport_number ?? '',
    passport_issue_date: visitor.document.passport_issue_date ?? '',
    passport_issued_by: visitor.document.passport_issued_by ?? '',
    passport_department_code: visitor.document.passport_department_code ?? '',
    
    license_series_number: visitor.document.license_series_number_for_input ?? '',
    license_issue_date: visitor.document.license_issue_date ?? '',
    license_region: visitor.document.license_region ?? '',
    license_issued_by: visitor.document.license_issued_by ?? '',
    
    other_document_name: visitor.document.other_document_name ?? '',
    other_series_number: visitor.document.other_series_number_original ?? '',
    other_issue_date: visitor.document.other_issue_date ?? '',
    other_issued_by: visitor.document.other_issued_by ?? '',
    
    entry_datetime: visitor.entry_datetime_for_input,
    exit_datetime: visitor.exit_datetime_for_input,
    
    remarks: visitor.remarks ?? '',
})
</script>

<template>
    <Head title="Редактировать посетителя" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-4xl">
                <form @submit.prevent="form.put(visitorsUpdate(visitor.id).url)">
                    <FieldGroup>
                        <FieldSet>
                            <FieldLegend>Редактировать посетителя</FieldLegend>
                            <FieldDescription>
                                Пожалуйста, заполните все необходимые поля для изменения посетителя.
                            </FieldDescription>
                            
                            <FieldGroup class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <!--ФИО-->
                                <Field :data-invalid="!!form.errors.full_name" class="sm:col-span-3">
                                    <FieldLabel for="full_name">ФИО*</FieldLabel>
                                    <Input v-model="form.full_name" :aria-invalid="!!form.errors.full_name" id="full_name" required />
                                    <FieldError v-if="form.errors.full_name" :errors="[form.errors.full_name]" />
                                </Field>
                                <!--Отдел-->
                                <Field :data-invalid="!!form.errors.department" class="sm:col-span-3">
                                    <FieldLabel>Отдел*</FieldLabel>
                                    <Select v-model="form.department" :aria-invalid="!!form.errors.department" :disabled="departments.length === 0" required>
                                        <SelectTrigger>
                                            <SelectValue placeholder="Выберите отдел" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="department in departments" :key="department.id" :value="department.id">
                                                {{ department.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <FieldError v-if="form.errors.department" :errors="[form.errors.department]" />
                                </Field>
                                <!--Дата рождения-->
                                <Field :data-invalid="!!form.errors.birth_date" class="sm:col-span-3">
                                    <FieldLabel for="birth_date">Дата рождения*</FieldLabel>
                                    <Input v-model="form.birth_date" :aria-invalid="!!form.errors.birth_date" id="birth_date" type="date" required />
                                    <FieldError v-if="form.errors.birth_date" :errors="[form.errors.birth_date]" />
                                </Field>
                                <!--Должность-->
                                <Field :data-invalid="!!form.errors.position" class="sm:col-span-3">
                                    <FieldLabel for="position">Должность*</FieldLabel>
                                    <Input v-model="form.position" :aria-invalid="!!form.errors.position" id="position" required />
                                    <FieldError v-if="form.errors.position" :errors="[form.errors.position]" />
                                </Field>
                                <!--Телефон-->
                                <Field :data-invalid="!!form.errors.phone" class="sm:col-span-3">
                                    <FieldLabel for="phone">Номер телефона*</FieldLabel>
                                    <Input v-model="form.phone" :aria-invalid="!!form.errors.phone" id="phone" type="tel" placeholder="+7(000)000-00-00" required />
                                    <FieldError v-if="form.errors.phone" :errors="[form.errors.phone]" />
                                </Field>
                            </FieldGroup>
                        </FieldSet>
                        <FieldSet>
                            <FieldLegend>Документ, удостоверяющий личность</FieldLegend>
                            <FieldDescription>
                                Пожалуйста, выберите один из предложенных вариантов.
                            </FieldDescription>
                            <RadioGroup v-model="form.document_type" class="mt-4 flex gap-12">
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem id="passport" value="passport" />
                                    <Label for="passport">Паспорт</Label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem id="license" value="license" />
                                    <Label for="license">Водительское удостоверение</Label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem id="other" value="other" />
                                    <Label for="other">Прочее</Label>
                                </div>
                            </RadioGroup>
                        </FieldSet>
                        
                        <FieldSet :class="form.document_type === 'passport' ? 'mb-10' : 'mb-35'">
                            <!--Поля при выборе паспорта-->
                            <FieldGroup v-if="form.document_type === 'passport'" class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <!--Серия-->
                                <Field :data-invalid="!!form.errors.passport_series" class="sm:col-span-3">
                                    <FieldLabel for="passport_series">Серия*</FieldLabel>
                                    <Input v-model="form.passport_series" :aria-invalid="!!form.errors.passport_series" id="passport_series" placeholder="0000" maxlength="4" required />
                                    <FieldError v-if="form.errors.passport_series" :errors="[form.errors.passport_series]" />
                                </Field>
                                <!--Номер-->
                                <Field :data-invalid="!!form.errors.passport_number" class="sm:col-span-3">
                                    <FieldLabel for="passport_number">Номер*</FieldLabel>
                                    <Input v-model="form.passport_number" :aria-invalid="!!form.errors.passport_number" id="passport_number" placeholder="000000" maxlength="6" required />
                                    <FieldError v-if="form.errors.passport_number" :errors="[form.errors.passport_number]" />
                                </Field>
                                <!--Дата выдачи-->
                                <Field :data-invalid="!!form.errors.passport_issue_date" class="sm:col-span-3">
                                    <FieldLabel for="passport_issue_date">Дата выдачи*</FieldLabel>
                                    <Input v-model="form.passport_issue_date" :aria-invalid="!!form.errors.passport_issue_date" id="passport_issue_date" type="date" required />
                                    <FieldError v-if="form.errors.passport_issue_date" :errors="[form.errors.passport_issue_date]" />
                                </Field>
                                <!--Кем выдан-->
                                <Field :data-invalid="!!form.errors.passport_issued_by" class="sm:col-span-3">
                                    <FieldLabel for="passport_issued_by">Кем выдан*</FieldLabel>
                                    <Input v-model="form.passport_issued_by" :aria-invalid="!!form.errors.passport_issued_by" id="passport_issued_by" required />
                                    <FieldError v-if="form.errors.passport_issued_by" :errors="[form.errors.passport_issued_by]" />
                                </Field>
                                <!--Код подразделения-->
                                <Field :data-invalid="!!form.errors.passport_department_code" class="sm:col-span-3">
                                    <FieldLabel for="passport_department_code">Код подразделения*</FieldLabel>
                                    <Input v-model="form.passport_department_code" :aria-invalid="!!form.errors.passport_department_code" id="passport_department_code" placeholder="000-000" required />
                                    <FieldError v-if="form.errors.passport_department_code" :errors="[form.errors.passport_department_code]" />
                                </Field>
                            </FieldGroup>
                            
                            <!--Поля при выборе водительского-->
                            <FieldGroup v-if="form.document_type === 'license'" class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <!--Серия и номер-->
                                <Field :data-invalid="!!form.errors.license_series_number" class="sm:col-span-3">
                                    <FieldLabel for="license_series_number">Серия и номер*</FieldLabel>
                                    <Input v-model="form.license_series_number" :aria-invalid="!!form.errors.license_series_number" id="license_series_number" placeholder="0000000000" maxlength="10" required />
                                    <FieldError v-if="form.errors.license_series_number" :errors="[form.errors.license_series_number]" />
                                </Field>
                                <!--Дата выдачи-->
                                <Field :data-invalid="!!form.errors.license_issue_date" class="sm:col-span-3">
                                    <FieldLabel for="license_issue_date">Дата выдачи*</FieldLabel>
                                    <Input v-model="form.license_issue_date" :aria-invalid="!!form.errors.license_issue_date" id="license_issue_date" type="date" required />
                                    <FieldError v-if="form.errors.license_issue_date" :errors="[form.errors.license_issue_date]" />
                                </Field>
                                <!--Регион-->
                                <Field :data-invalid="!!form.errors.license_region" class="sm:col-span-3">
                                    <FieldLabel for="license_region">Регион*</FieldLabel>
                                    <Input v-model="form.license_region" :aria-invalid="!!form.errors.license_region" id="license_region" required />
                                    <FieldError v-if="form.errors.license_region" :errors="[form.errors.license_region]" />
                                </Field>
                                <!--Кем выдан-->
                                <Field :data-invalid="!!form.errors.license_issued_by" class="sm:col-span-3">
                                    <FieldLabel for="license_issued_by">Кем выдан*</FieldLabel>
                                    <Input v-model="form.license_issued_by" :aria-invalid="!!form.errors.license_issued_by" id="license_issued_by" required />
                                    <FieldError v-if="form.errors.license_issued_by" :errors="[form.errors.license_issued_by]" />
                                </Field>
                            </FieldGroup>
                            
                            <!--Поля при выборе другого документа-->
                            <FieldGroup v-if="form.document_type === 'other'" class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <!--Название документа-->
                                <Field :data-invalid="!!form.errors.other_document_name" class="sm:col-span-3">
                                    <FieldLabel for="other_document_name">Название документа*</FieldLabel>
                                    <Input v-model="form.other_document_name" :aria-invalid="!!form.errors.other_document_name" id="other_document_name" required />
                                    <FieldError v-if="form.errors.other_document_name" :errors="[form.errors.other_document_name]" />
                                </Field>
                                <!--Серия и номер-->
                                <Field :data-invalid="!!form.errors.other_series_number" class="sm:col-span-3">
                                    <FieldLabel for="other_series_number">Серия и номер*</FieldLabel>
                                    <Input v-model="form.other_series_number" :aria-invalid="!!form.errors.other_series_number" id="other_series_number" required />
                                    <FieldError v-if="form.errors.other_series_number" :errors="[form.errors.other_series_number]" />
                                </Field>
                                <!--Дата выдачи-->
                                <Field :data-invalid="!!form.errors.other_issue_date" class="sm:col-span-3">
                                    <FieldLabel for="other_issue_date">Дата выдачи*</FieldLabel>
                                    <Input v-model="form.other_issue_date" :aria-invalid="!!form.errors.other_issue_date" id="other_issue_date" type="date" required />
                                    <FieldError v-if="form.errors.other_issue_date" :errors="[form.errors.other_issue_date]" />
                                </Field>
                                <!--Кем выдан-->
                                <Field :data-invalid="!!form.errors.other_issued_by" class="sm:col-span-3">
                                    <FieldLabel for="other_issued_by">Кем выдан*</FieldLabel>
                                    <Input v-model="form.other_issued_by" :aria-invalid="!!form.errors.other_issued_by" id="other_issued_by" required />
                                    <FieldError v-if="form.errors.other_issued_by" :errors="[form.errors.other_issued_by]" />
                                </Field>
                            </FieldGroup>
                        </FieldSet>
                        <FieldSet>
                            <FieldGroup class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <!--Дата и время входа-->
                                <Field :data-invalid="!!form.errors.entry_datetime" class="sm:col-span-3">
                                    <FieldLabel for="entry_datetime">Дата и время входа*</FieldLabel>
                                    <Input v-model="form.entry_datetime" :aria-invalid="!!form.errors.entry_datetime" id="entry_datetime" type="datetime-local" required />
                                    <FieldError v-if="form.errors.entry_datetime" :errors="[form.errors.entry_datetime]" />
                                </Field>
                                <!--Дата и время выхода-->
                                <Field :data-invalid="!!form.errors.exit_datetime" class="sm:col-span-3">
                                    <FieldLabel for="exit_datetime">Дата и время выхода*</FieldLabel>
                                    <Input v-model="form.exit_datetime" :aria-invalid="!!form.errors.exit_datetime" id="exit_datetime" type="datetime-local" required />
                                    <FieldError v-if="form.errors.exit_datetime" :errors="[form.errors.exit_datetime]" />
                                </Field>
                            </FieldGroup>
                            <FieldGroup>
                                <!--Замечание-->
                                <Field :data-invalid="!!form.errors.remarks">
                                    <FieldLabel for="remarks">Замечание</FieldLabel>
                                    <Textarea v-model="form.remarks" :aria-invalid="!!form.errors.remarks" id="remarks" class="resize-none" />
                                    <FieldError v-if="form.errors.remarks" :errors="[form.errors.remarks]" />
                                </Field>
                            </FieldGroup>
                        </FieldSet>
                        <Field orientation="horizontal">
                            <Button type="submit">
                                Сохранить
                            </Button>
                            <Button @click="router.get(visitorsIndex().url)" variant="outline" type="button">
                                Отмена
                            </Button>
                            <small class="underline ml-10">Поля со <span class="text-destructive">звездочкой*</span> обязательны для заполнения.</small>
                        </Field>
                    </FieldGroup>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
