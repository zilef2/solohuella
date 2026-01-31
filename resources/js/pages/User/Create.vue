<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { watchEffect, ref } from 'vue';

import { VueDatePicker } from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const props = defineProps({
    show: Boolean,
    title: String,
    roles: Object,
    titulos: Object, //parametros de la clase principal
});
const emit = defineEmits(['close']);

// VueDatePicker
const formatToVue = (date:any) => {
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();

    return `${day}/${month}/${year}`;
};
const flow = ref(['year', 'month', 'calendar']);
const anioHoy = new Date().getFullYear();

const form = useForm({
    name: '',
    email: '',
    role: 'trabajador',

    identificacion: '',
    sexo: '',
    fecha_nacimiento: '',

    cargo: '',
    celular: '',
    area: '',
    password: '',
    password_confirmation: '',
});

const create = () => {
    form.post(route('user.store'), {
        preserveScroll: true,
        onSuccess: () => {
            emit('close');
            form.reset();
        },
        onError: () => null,
        onFinish: () => null,
    });
};

watchEffect(() => {
    if (props.show) {
        form.errors = {};
    }
    if (form.fecha_nacimiento) anio = parseInt(anioHoy - new Date(form.fecha_nacimiento).getFullYear());
    if (form.semestre) anio = 2024;
});
//TOSTUDY
const roles = props.roles?.map((role) => ({
    label: role.name.replace(/_/g, ' '),
    value: role.name,
}));

//very usefull
const sexos = [
    { label: 'Masculino', value: 'Masculino' },
    { label: 'Femenino', value: 'Femenino' },
];
const daynames = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];
</script>

<template>
    <section class="space-y-6">
        <Modal :show="props.show" @close="emit('close')">
            <form class="p-6 pb-8 md:pb-28" @submit.prevent="create">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ lang().label.add }} {{ props.title }}</h2>
                <h3 class="mb-2 font-medium text-gray-900 dark:text-gray-100">
                    {{ lang().label.RequiredFields }}
                </h3>
                <div class="mt-6 mb-20 space-y-4">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <div class="inline-flex">
                                <InputLabel for="name" :value="lang().label.name" />
                                <small class="ml-1 text-lg font-bold"> {{ titulos[0].required ? '*' : 'ã…¤' }} </small>
                            </div>
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                :placeholder="lang().placeholder.name"
                                :error="form.errors.name"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div>
                            <div class="inline-flex">
                                <InputLabel for="email" :value="lang().label.email" />
                                <small class="ml-1 text-lg font-bold"> * </small>
                            </div>
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                :placeholder="lang().placeholder.email"
                                :error="form.errors.email"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                        <div>
                            <div class="inline-flex">
                                <InputLabel for="identificacion" :value="lang().label.identificacion" />
                                <small class="ml-1 text-lg font-bold"> * </small>
                            </div>
                            <TextInput
                                id="identificacion"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.identificacion"
                                :placeholder="lang().placeholder.identificacion"
                                :error="form.errors.identificacion"
                            />
                            <InputError class="mt-2" :message="form.errors.identificacion" />
                        </div>

                        <div>
                            <div class="inline-flex">
                                <InputLabel for="area" :value="lang().label.area" />
                                <small class="ml-1 text-lg font-bold"> * </small>
                            </div>
                            <TextInput
                                id="area"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.area"
                                :placeholder="lang().placeholder.area"
                                :error="form.errors.area"
                            />
                            <InputError class="mt-2" :message="form.errors.area" />
                        </div>
                        <div>
                            <div class="inline-flex">
                                <InputLabel for="cargo" :value="lang().label.cargo" />
                                <small class="ml-1 text-lg font-bold"> * </small>
                            </div>
                            <TextInput
                                id="cargo"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.cargo"
                                :placeholder="lang().placeholder.cargo"
                                :error="form.errors.cargo"
                            />
                            <InputError class="mt-2" :message="form.errors.cargo" />
                        </div>

                        <!-- otros campos -->
                        <div>
                            <div class="inline-flex">
                                <InputLabel for="sexo" :value="lang().label.sexo" />
                                <small class="ml-1 text-lg font-bold">ã…¤</small>
                            </div>
                            <SelectInput id="sexo" class="mt-1 block w-full" v-model="form.sexo" required :dataSet="sexos"> </SelectInput>
                            <InputError class="mt-2" :message="form.errors.sexo" />
                        </div>
                        <div>
                            <div class="inline-flex">
                                <InputLabel for="celular" :value="lang().label.celular" />
                                <small class="ml-1 text-lg font-bold">ã…¤</small>
                            </div>
                            <TextInput id="celular" class="mt-1 block w-full" type="number" v-model="form.celular"> </TextInput>
                            <InputError class="mt-2" :message="form.errors.celular" />
                        </div>
                        <div>
                            <div class="inline-flex">
                                <InputLabel for="fecha_nacimiento" :value="lang().label.fecha_nacimiento" />
                                <small class="ml-1 text-lg font-bold">ã…¤</small>
                            </div>
                            <VueDatePicker
                                :is-24="false"
                                :day-names="daynames"
                                :format="formatToVue"
                                :flow="flow"
                                auto-apply
                                :enable-time-picker="false"
                                id="fecha_nacimiento"
                                class="mt-1 block w-full"
                                v-model="form.fecha_nacimiento"
                                required
                                :placeholder="lang().placeholder.fecha_nacimiento"
                                :error="form.errors.fecha_nacimiento"
                            />
                            <InputError class="mt-2" :message="form.errors.fecha_nacimiento" />
                        </div>

                        <div>
                            <div class="inline-flex">
                                <InputLabel for="role" :value="lang().label.role" />
                                <small class="ml-1 text-lg font-bold"> * </small>
                            </div>
                            <SelectInput id="role" class="mt-1 block w-full" v-model="form.role" required :dataSet="roles"> </SelectInput>
                            <InputError class="mt-2" :message="form.errors.role" />
                        </div>
                    </div>
                    <!-- <div>
                        <InputLabel for="password" :value="lang().label.password" />
                        <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                            :placeholder="lang().placeholder.password" :error="form.errors.password" />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                    <div>
                        <InputLabel for="password_confirmation" :value="lang().label.password_confirmation" />
                        <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                            v-model="form.password_confirmation" :placeholder="lang().placeholder.password_confirmation"
                            :error="form.errors.password_confirmation" />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div> -->
                </div>
                <div class="flex justify-end">
                    <SecondaryButton :disabled="form.processing" @click="emit('close')"> {{ lang().button.close }} </SecondaryButton>
                    <PrimaryButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="create">
                        {{ form.processing ? lang().button.add + '...' : lang().button.add }}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    </section>
</template>

