<template>
    <form @submit.prevent="submit">
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <div
                    class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                >
                    <div class="sm:col-span-4">
                        <!-- Category Name -->
                        <label
                            for="name"
                            class="block text-sm font-medium leading-6 text-gray-900"
                            >Category Name:</label
                        >
                        <div class="mt-2">
                            <div
                                class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md"
                            >
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    v-model="name"
                                    autocomplete="name"
                                    class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                    placeholder="Category 1"
                                />
                            </div>
                        </div>
                        <div v-if="error1" class="mt-2 text-sm text-red-600">
                            {{ error1 }}
                        </div>
                        <!-- Category Description -->
                        <label
                            for="name"
                            class="block text-sm font-medium leading-6 text-gray-900"
                            >Description:</label
                        >

                        <div class="mt-2">
                            <div
                                class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md"
                            >
                                <input
                                    type="text"
                                    name="description"
                                    id="description"
                                    v-model="description"
                                    autocomplete="description"
                                    class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                    placeholder="Description"
                                />
                            </div>
                        </div>
                        <div v-if="error2" class="mt-2 text-sm text-red-600">
                            {{ error2 }}
                        </div>

                        <!-- Upload Image -->
                        <div class="col-span-full">
                            <label
                                for="cover-photo"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Cover photo</label
                            >
                            <div
                                class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
                            >
                                <div class="text-center">
                                    <PhotoIcon
                                        class="mx-auto h-12 w-12 text-gray-300"
                                        aria-hidden="true"
                                    />
                                    <div
                                        class="mt-4 flex text-sm leading-6 text-gray-600"
                                    >
                                        <label
                                            for="photo"
                                            class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500"
                                        >
                                            <span>Upload a photo</span>
                                            <input
                                                id="photo"
                                                name="photo"
                                                type="file"
                                                class="sr-only"
                                                @change="handleFileUpload"
                                            />
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs leading-5 text-gray-600">
                                        PNG, JPG, GIF up to 10MB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div v-if="success" class="mt-2 text-sm text-green-600">
                            {{ success }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-left gap-x-6">
            <button
                type="submit"
                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
                Submit
            </button>
        </div>
    </form>

    <!-- All Product with pagination -->
    <div class="grid grid-cols-4 gap-4 mt-10">
        <div
            class="space-y-2"
            v-for="product in products.data"
            :key="product.id"
        >
            <a href="#">
                <img src="http://placehold.it/300x400" :alt="product.name" />
            </a>
            <a class="text-slate-500 text-xl font-semibold hover:underline">
                {{ product.name }}
            </a>
            <p>${{ product.price }}</p>
            <p class="prose-slate">{{ product.description }}</p>
        </div>
    </div>

    <TailwindPagination
        :data="products"
        @pagination-change-page="getProducts"
        class="mt-4"
    />
</template>

<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";

const categories = ref({});
const products = ref({});

const name = ref("");
const description = ref("");
const photo = ref(null);
const error1 = ref("");
const error2 = ref("");
const success = ref("");

const handleFileUpload = (event) => {
    photo.value = event.target.files[0];
};

const submit = () => {
    error1.value = "";
    error2.value = "";
    success.value = "";

    // Check if the name field is empty
    if (!name.value) {
        error1.value = "Category name is required.";
        error2.value = "Category description is required.";
        return;
    }

    const formData = new FormData();
    formData.append("name", name.value);
    formData.append("description", description.value);
    if (photo.value) {
        formData.append("photo", photo.value);
    }

    axios
        .post("/api/categories", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((response) => {
            console.log("New Category ID: " + response.data.data.id);
            (name.value = ""),
                (description.value = ""),
                (photo.value = null),
                (success.value = "Success.");
        })
        .catch((error) => {
            console.error("There was an error creating the category!", error);
        });
};

const getProducts = async (page = 1) => {
    await axios
        .get(`/api/products?page=${page}`)
        .then((response) => {
            products.value = response.data;
        })
        .catch((error) => console.log(error));
};

const getCategories = async () => {
    await axios
        .get("/api/categories")
        .then((response) => {
            categories.value = response.data.data;
        })
        .catch((error) => console.log(error));
};

onMounted(() => {
    getCategories();
    getProducts();
});
</script>
