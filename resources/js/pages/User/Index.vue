<script setup lang="ts">
import {Button} from '@/components/ui/button';
import {Input} from '@/components/ui/input';
import {Spinner} from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import {Field, FieldError, FieldGroup, FieldLabel, FieldSet} from "@/components/ui/field";

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();

const form = useForm({
    username: '',
    password: '',
});

</script>

<template>
    <AuthBase title="Войдите в свою учетную запись" description="Логин – admin, пароль – password">
        <Head title="Вход" />
        
        <form>
            <FieldGroup>
                <FieldSet>
                    <FieldGroup>
                        <!-- Логин -->
                        <Field :data-invalid="!!form.errors.username">
                            <FieldLabel for="entry_datetime">Логин</FieldLabel>
                            <Input v-model="form.username" :aria-invalid="!!form.errors.username" id="username" type="text" required autofocus />
                            <FieldError v-if="form.errors.username" :errors="[form.errors.username]" />
                        </Field>
                        <!-- Пароль -->
                        <Field :data-invalid="!!form.errors.password">
                            <FieldLabel for="password">Пароль</FieldLabel>
                            <Input v-model="form.password" :aria-invalid="!!form.errors.password" id="password" type="password" required />
                            <FieldError v-if="form.errors.password" :errors="[form.errors.password]" />
                        </Field>
                    </FieldGroup>
                </FieldSet>
                <Field>
                    <Button type="submit" :disabled="form.processing">
                        <Spinner v-if="form.processing" />
                        Войти
                    </Button>
                </Field>
            </FieldGroup>
        </form>
    </AuthBase>
</template>
