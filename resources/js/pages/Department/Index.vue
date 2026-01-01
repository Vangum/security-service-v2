<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem} from '@/types';
import {Head, router, useForm} from '@inertiajs/vue3';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger
} from "@/components/ui/alert-dialog";
import {Button} from '@/components/ui/button';
import {Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle} from '@/components/ui/dialog';
import {Field, FieldError, FieldLabel} from '@/components/ui/field';
import {Input} from '@/components/ui/input';
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from '@/components/ui/table';
import {ChevronLeft, ChevronRight, Pen, SearchIcon, Trash, X} from 'lucide-vue-next';
import {departmentsDestroy, departmentsIndex, departmentsStore, departmentsUpdate} from "@/routes";
import {ref} from "vue";
import {Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue} from "@/components/ui/select";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Справочники',
        href: departmentsIndex().url,
    },
];

interface Department {
    id: number;
    name: string;
}

interface PaginationLink {
    url: string | null
    label: string
    active: boolean
}

interface PaginatedData<T> {
    data: T[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
    links: PaginationLink[]
}


const {departments, filters} = defineProps<{
    departments: PaginatedData<Department>;
    filters: { per_page: number, search: string };
}>();

const isLoading = ref(false);
const perPage = ref(filters.per_page);
const searchQuery = ref(filters.search ?? '')

const showCreateDialog = ref(false);
const showEditDialog = ref(false);

const createForm = useForm({
    name: '',
});

const editForm = useForm({
    id: null as number | null,
    name: '',
});

const openCreateDialog = () => {
    createForm.reset();
    createForm.clearErrors();
    showCreateDialog.value = true;
};

const openEditDialog = (department: Department) => {
    editForm.clearErrors();
    editForm.id = department.id;
    editForm.name = department.name;
    showEditDialog.value = true;
};

const createDepartment = () => {
    createForm.post(departmentsStore().url, {
        onSuccess: () => {
            showCreateDialog.value = false;
            createForm.reset();
        },
    });
};

const updateDepartment = () => {
    if (editForm.id === null) return;
    
    editForm.put(departmentsUpdate(editForm.id).url, {
        onSuccess: () => {
            showEditDialog.value = false;
            editForm.reset();
        },
    });
};

const searchDepartments = () => {
    isLoading.value = true;
    router.get(departmentsIndex().url, {
        search: searchQuery.value,
        per_page: perPage.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            isLoading.value = false;
        }
    })
}

const clearSearch = () => {
    searchQuery.value = '';
    isLoading.value = true;
    router.get(departmentsIndex().url, {
        per_page: perPage.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            isLoading.value = false;
        }
    })
}

const changePerPage = (value: string | number) => {
    perPage.value = Number(value)
    isLoading.value = true;
    
    router.get(departmentsIndex().url, {
        page: 1,
        per_page: perPage.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            isLoading.value = false;
        }
    })
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Справочники" />
        
        <div class="flex h-full flex-col gap-4 p-4">
            
            <div v-if="departments.total === 0 && !searchQuery && !isLoading" class="text-center ">
                <span>Справочников пока нет.&nbsp;
                    <Button @click="openCreateDialog" variant="link" class="cursor-pointer p-0 m-0 font-normal text-md underline">Добавить</Button>
                </span>
            </div>
            
            <!-- Загрузка -->
            <div v-else-if="isLoading" class="flex flex-col h-full gap-4">
                <!-- Верхняя панель -->
                <div class="flex items-center gap-4 flex-shrink-0">
                    <div class="flex w-full max-w-sm items-center space-x-2">
                        <div class="relative flex-1">
                            <Input v-model="searchQuery" type="text" placeholder="Поиск..." @keyup.enter="searchDepartments" class="pr-8" :disabled="isLoading" />
                            <button v-if="searchQuery" @click="clearSearch" class="absolute right-2 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground" :disabled="isLoading">
                                <X class="h-4 w-4" />
                            </button>
                        </div>
                        <Button @click="searchDepartments" :disabled="!searchQuery || isLoading" variant="outline">
                            <SearchIcon class="h-4 w-4" />
                        </Button>
                    </div>
                    
                    <Button @click="openCreateDialog" class="ml-auto" :disabled="isLoading">Добавить</Button>
                </div>
                
                <!-- Загрузка -->
                <div class="flex flex-1 items-center justify-center">
                    <div class="flex gap-1">
                        <div class="w-2 h-2 bg-primary rounded-full animate-bounce" style="animation-delay: 0s"></div>
                        <div class="w-2 h-2 bg-primary rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-2 h-2 bg-primary rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                    </div>
                </div>
            </div>
            
            <div v-else class="flex flex-col h-full gap-4">
                <!-- Верхняя панель -->
                <div class="flex items-center gap-4 flex-shrink-0">
                    <div class="flex w-full max-w-sm items-center space-x-2">
                        <div class="relative flex-1">
                            <Input v-model="searchQuery" type="text" placeholder="Поиск..." @keyup.enter="searchDepartments" class="pr-8" :disabled="isLoading" />
                            <button v-if="searchQuery" @click="clearSearch" class="absolute right-2 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground" :disabled="isLoading">
                                <X class="h-4 w-4" />
                            </button>
                        </div>
                        <Button @click="searchDepartments" :disabled="!searchQuery || isLoading" variant="outline">
                            <SearchIcon class="h-4 w-4" />
                        </Button>
                    </div>
                    
                    <Button @click="openCreateDialog" class="ml-auto" :disabled="isLoading">Добавить</Button>
                </div>
                
                <!-- Сообщение - ничего не найдено -->
                <div v-if="departments.total === 0 && searchQuery" class="text-center py-8">
                    <p class="text-muted-foreground mb-4">
                        По запросу "{{ searchQuery }}" ничего не найдено
                    </p>
                </div>
                
                <!-- Таблица -->
                <div v-else class="flex flex-col flex-1 min-h-0 gap-4">
                    <!-- Таблица с прокруткой -->
                    <div class="rounded-md border flex-1 overflow-hidden flex flex-col">
                        <!-- Шапка таблицы -->
                        <Table class="flex-shrink-0 table-fixed">
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="w-[100px]">ID</TableHead>
                                    <TableHead>Название элемента справочника</TableHead>
                                    <TableHead class="w-[100px] text-right">Действия</TableHead>
                                </TableRow>
                            </TableHeader>
                        </Table>
                        
                        <!-- Тело таблицы -->
                        <div class="overflow-y-auto flex-1">
                            <Table class="table-fixed">
                                <TableBody>
                                    <TableRow v-for="department in departments.data" :key="department.id">
                                        <TableCell class="font-medium w-[100px]">{{ department.id }}</TableCell>
                                        <TableCell>{{ department.name }}</TableCell>
                                        <TableCell class="w-[100px] text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <Button @click="openEditDialog(department)" variant="outline" size="icon" aria-label="Submit" :disabled="isLoading">
                                                    <Pen />
                                                </Button>
                                                <AlertDialog>
                                                    <AlertDialogTrigger as-child>
                                                        <Button variant="outline" size="icon" :disabled="isLoading">
                                                            <Trash />
                                                        </Button>
                                                    </AlertDialogTrigger>
                                                    <AlertDialogContent>
                                                        <AlertDialogHeader>
                                                            <AlertDialogTitle>Вы уверены?</AlertDialogTitle>
                                                            <AlertDialogDescription>
                                                                Это действие удалит отдел. Вы действительно хотите продолжить?
                                                            </AlertDialogDescription>
                                                        </AlertDialogHeader>
                                                        <AlertDialogFooter>
                                                            <AlertDialogCancel>Отмена</AlertDialogCancel>
                                                            <AlertDialogAction @click="router.delete(departmentsDestroy(department).url)">Удалить</AlertDialogAction>
                                                        </AlertDialogFooter>
                                                    </AlertDialogContent>
                                                </AlertDialog>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </div>
                    
                    <!-- Нижняя панель с пагинацией -->
                    <div class="flex justify-between items-center flex-shrink-0">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600 dark:text-white">Записей на странице:</span>
                            <Select :model-value="String(perPage)" @update:model-value="value => changePerPage(value as string | number)" :disabled="isLoading">
                                <SelectTrigger class="w-[80px]">
                                    <SelectValue placeholder="Количество записей" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="5">5</SelectItem>
                                        <SelectItem value="10">10</SelectItem>
                                        <SelectItem value="20">20</SelectItem>
                                        <SelectItem value="50">50</SelectItem>
                                        <SelectItem value="100">100</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="flex items-center gap-1">
                            <Button
                                size="icon"
                                variant="ghost"
                                :disabled="!departments.links[0].url || isLoading"
                                @click="departments.links[0].url && router.visit(departments.links[0].url)"
                            >
                                <ChevronLeft class="w-4 h-4" />
                            </Button>
                            
                            <Button
                                v-for="(link, i) in departments.links.slice(1, -1)"
                                :key="i"
                                size="sm"
                                :variant="link.active ? 'outline' : 'ghost'"
                                :disabled="!link.url || isLoading"
                                @click="link.url && router.visit(link.url)"
                            >
                                {{ link.label }}
                            </Button>
                            
                            <Button
                                size="icon"
                                variant="ghost"
                                :disabled="!departments.links.at(-1)?.url || isLoading"
                                @click="() => {
                                    const lastLink = departments.links.at(-1)
                                    if (lastLink?.url) {
                                        router.visit(lastLink.url, { preserveScroll: true, preserveState: true })
                                    }
                                }"
                            >
                                <ChevronRight class="w-4 h-4" />
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Dialog для создания -->
            <Dialog v-model:open="showCreateDialog">
                <DialogContent class="sm:max-w-[525px]">
                    <form @submit.prevent="createDepartment">
                        <DialogHeader>
                            <DialogTitle>Добавить справочник</DialogTitle>
                            <DialogDescription>Пожалуйста, введите название справочника. После завершения нажмите «Сохранить».</DialogDescription>
                        </DialogHeader>
                        <Field class="my-4" :data-invalid="!!createForm.errors.name">
                            <FieldLabel for="create-name">Название*</FieldLabel>
                            <Input id="create-name" v-model="createForm.name" :aria-invalid="!!createForm.errors.name" placeholder="Введите название" />
                            <FieldError v-if="createForm.errors.name" :errors="[createForm.errors.name]" />
                        </Field>
                        <DialogFooter>
                            <Button type="submit">Сохранить</Button>
                            <DialogClose as-child>
                                <Button variant="outline">Отмена</Button>
                            </DialogClose>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
            
            <!-- Dialog для редактирования -->
            <Dialog v-model:open="showEditDialog">
                <DialogContent class="sm:max-w-[525px]">
                    <form @submit.prevent="updateDepartment">
                        <DialogHeader>
                            <DialogTitle>Редактировать справочник</DialogTitle>
                            <DialogDescription>Пожалуйста, измените название справочника. После завершения нажмите «Сохранить».</DialogDescription>
                        </DialogHeader>
                        <Field class="my-4" :data-invalid="!!editForm.errors.name">
                            <FieldLabel for="edit-name">Название*</FieldLabel>
                            <Input id="edit-name" v-model="editForm.name" :aria-invalid="!!editForm.errors.name" placeholder="Введите название" />
                            <FieldError v-if="editForm.errors.name" :errors="[editForm.errors.name]" />
                        </Field>
                        <DialogFooter>
                            <Button type="submit">Сохранить</Button>
                            <DialogClose as-child>
                                <Button variant="outline">Отмена</Button>
                            </DialogClose>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>