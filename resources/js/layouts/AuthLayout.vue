<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <AuthLayout
        :title="lang().t('app.initial.title', 'Título por defecto')"
        :description="lang().t('app.initial.Log_in_to_your_account', 'Descripción por defecto')"
    >
        <slot />
    </AuthLayout>
</template>
