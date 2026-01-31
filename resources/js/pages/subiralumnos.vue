<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import PrimaryButton from '@/components/PrimaryButton.vue';
// import pkg from 'lodash';
import { ArrowUpCircleIcon } from '@heroicons/vue/24/solid';
import { useForm } from '@inertiajs/vue3';
import '@vuepic/vue-datepicker/dist/main.css';

// const { _, debounce, pickBy } = pkg

// const data = reactive({
// })

const form = useForm<{ archivo1: File | null; }>({
    archivo1: null,
});

function uploadtrabajadorsAlumnoss() {
    form.post(route('uploadAlumnoss'), {
        preserveScroll: true,
        onSuccess: () => {
            // emit("close")
            // form.reset()
            // data.respuesta = $page.props.flash.success
        },
        onError: () => null,
        onFinish: () => null,
    });
}

const onFileChange = (e: Event) => {
    const input = e.target as HTMLInputElement | null;
    if (!input || !input.files || input.files.length === 0) return;

    form.archivo1 = input.files[0];
};


// const downloadExcel = () => { window.open('users/export/' + form.quincena + '/' + (form.fecha_ini.month) + '/' + form.fecha_ini.year, '_blank') }
</script>
<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
        <header class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Inicio
                </Link>
                <template v-else>
                    <Link
                        :href="route('alumnos.options')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Huella</Link
                    >

                    <Link
                        :href="route('alumnos.options')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Buscar alumno</Link
                    >

                    <Link
                        :href="route('subiralumnos')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Cargar BD</Link
                    >
                </template>
            </nav>
        </header>
        <div class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
            <main class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div
                    class="flex-1 rounded-br-lg rounded-bl-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:rounded-tl-lg lg:rounded-br-none lg:p-20 dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
                >
                    <h1 class="mb-1 font-medium">Comenzemos</h1>
                    <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">
                        Este proyecto es totalmente local <br />Te recomendamos utilizar un servidor
                    </p>
                    <ul class="mb-4 flex flex-col lg:mb-6">
                        <li
                            class="relative flex items-center gap-4 py-2 before:absolute before:top-1/2 before:bottom-0 before:left-[0.4rem] before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A]"
                        >
                            <span class="relative bg-white py-1 dark:bg-[#161615]">
                                <span
                                    class="flex h-3.5 w-3.5 items-center justify-center rounded-full border border-[#e3e3e0] bg-[#FDFDFC] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] dark:border-[#3E3E3A] dark:bg-[#161615]"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A]" />
                                </span>
                            </span>
                            <span>
                                24/7 disponible
                                <a
                                    href="#"
                                    target="_blank"
                                    class="ml-1 inline-flex items-center space-x-1 font-medium text-[#f53003] underline underline-offset-4 dark:text-[#FF4433]"
                                >
                                    <span> Almacenamiento en la nube </span>
                                    <svg
                                        width="{10}"
                                        height="{11}"
                                        viewBox="0 0 10 11"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-2.5 w-2.5"
                                    >
                                        <path d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001" stroke="currentColor" stroke-linecap="square" />
                                    </svg>
                                </a>
                            </span>
                        </li>
                        <li
                            class="relative flex items-center gap-4 py-2 before:absolute before:top-0 before:bottom-1/2 before:left-[0.4rem] before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A]"
                        >
                            <span class="relative bg-white py-1 dark:bg-[#161615]">
                                <span
                                    class="flex h-3.5 w-3.5 items-center justify-center rounded-full border border-[#e3e3e0] bg-[#FDFDFC] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] dark:border-[#3E3E3A] dark:bg-[#161615]"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A]" />
                                </span>
                            </span>
                            <span>
                                Sin riesgo de perdida de datos
                                <a
                                    href="https://www.hostgator.co/"
                                    target="_blank"
                                    class="ml-1 inline-flex items-center space-x-1 font-medium text-[#f53003] underline underline-offset-4 dark:text-[#FF4433]"
                                >
                                    <span>Online</span>
                                    <svg
                                        width="{10}"
                                        height="{11}"
                                        viewBox="0 0 10 11"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-2.5 w-2.5"
                                    >
                                        <path d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001" stroke="currentColor" stroke-linecap="square" />
                                    </svg>
                                </a>
                            </span>
                        </li>
                    </ul>
                    <ul class="flex gap-3 text-sm leading-normal">
                        <li>
                            <a
                                href="https://www.hostgator.co/"
                                target="_blank"
                                class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                            >
                                Descubre más
                            </a>
                        </li>
                    </ul>
                </div>
            </main>
        </div>
        <div class="hidden h-14.5 lg:block"></div>

        <div class="space-y-4">
            <div class="px-4 sm:px-0">
                <div class="w-fit overflow-hidden rounded-lg">
                    <div class="flex max-w-screen-xl rounded-lg shadow-lg">
                        <div class="flex items-center rounded-l-lg bg-yellow-600 px-6 py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="fill-current text-white" width="20" height="20">
                                <path
                                    fill-rule="evenodd"
                                    d="M8.22 1.754a.25.25 0 00-.44 0L1.698 13.132a.25.25 0 00.22.368h12.164a.25.25 0 00.22-.368L8.22 1.754zm-1.763-.707c.659-1.234 2.427-1.234 3.086 0l6.082 11.378A1.75 1.75 0 0114.082 15H1.918a1.75 1.75 0 01-1.543-2.575L6.457 1.047zM9 11a1 1 0 11-2 0 1 1 0 012 0zm-.25-5.25a.75.75 0 00-1.5 0v2.5a.75.75 0 001.5 0v-2.5z"
                                ></path>
                            </svg>
                        </div>
                        <div
                            class="flex w-full items-center justify-between rounded-r-lg border border-gray-200 border-l-transparent bg-white px-8 py-6"
                        >
                            <div></div>
                            <!-- <button>
                                <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-gray-700" viewBox="0 0 16 16" width="20" height="20"> <path fill-rule="evenodd" d="M3.72 3.72a.75.75 0 011.06 0L8 6.94l3.22-3.22a.75.75 0 111.06 1.06L9.06 8l3.22 3.22a.75.75 0 11-1.06 1.06L8 9.06l-3.22 3.22a.75.75 0 01-1.06-1.06L6.94 8 3.72 4.78a.75.75 0 010-1.06z"> </path> </svg>
                            </button> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative bg-white shadow sm:rounded-lg dark:bg-gray-800">
                <section class="body-font text-gray-600">
                    <div class="container mx-auto px-5 py-24">
                        <div class="-m-4 flex flex-wrap">
                            <!-- user_Alumnoss -->
                            <div class="p-4 md:w-1/2">
                                <div class="border-opacity-60 h-full overflow-hidden rounded-lg border-2 border-gray-200">
                                    <ArrowUpCircleIcon class="h-24 w-full object-cover object-center md:h-36 lg:h-48" />

                                    <div class="p-6">
                                        <h3 class="title-font mb-3 text-lg font-medium text-gray-900">Subir alumnos</h3>
                                        <p class="mb-3 leading-relaxed">Este formulario solo permite cargar alumnos</p>

                                        <form @submit.prevent="uploadtrabajadorsAlumnoss" id="upload">
                                            <div>
                                                <InputLabel for="AlumnossID" value="Alumnos" />
                                            </div>
                                            <input
                                                type="file"
                                                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                                @change="onFileChange"
                                            />

                                            <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="bg-sky-200">
                                                {{ form.progress.percentage }}%
                                            </progress>

                                            <PrimaryButton
                                                v-show="can(['create user'])"
                                                :disabled="form.archivo1 == null"
                                                class="mx-4 my-4 rounded-none"
                                                :class="{ 'bg-gray-200': form.archivo1 == null }"
                                            >
                                                {{ lang().button.subir }}
                                            </PrimaryButton>
                                        </form>

                                        <h2 class="text-xl text-gray-900 dark:text-white">El formato necesita las siguientes columnas</h2>
                                        <ul class="mx-5 my-6 list-decimal">
                                            <li class="text-lg">Identificacion : del alumno a inscribir</li>
                                        </ul>

                                        <!-- <div class="flex items-center flex-wrap my-6">
                                            <a class="text-gray-500 inline-flex items-center md:mb-2 lg:mb-0">Numero de Usuarios: </a>
                                            <span
                                                class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                                                <svg class="w-1 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"> <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path> <circle cx="12" cy="12" r="3"></circle> </svg>
                                                {{  props.numUsuarios }}
                                            </span>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <!-- user matriculas : multiple -->
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>
