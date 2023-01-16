<script setup>

import { useUserStore } from '../../stores/userStore.js';

// Stores

const userStore = useUserStore();

</script>

<template>
    <header>
        <nav class="bg-success navbar navbar-expand" data-bs-theme="dark">
            <div class="container">
                <div class="w-100">
                    <ul class="navbar-nav d-flex justify-content-center">
                        <div
                            class="row w-100 py-1 align-items-center d-flex justify-content-between justify-content-sm-end">
                            <div class="me-auto col-auto ps-0">
                                <li class="nav-item me-auto d-flex align-items-center">
                                    <router-link class="navbar-brand me-auto p-0 fs-4" :to="{ name: 'issues' }">
                                        The Force Track
                                    </router-link>
                                </li>
                            </div>
                            <div class="col-auto ms-auto">
                                <li class="nav-item">
                                    <router-link class="nav-link p-0" activeClass="active" :to="{ name: 'issues' }"><i
                                            class="d-sm-none fa-solid fa-xl fa-home"></i><span
                                            class="d-none d-sm-block fs-5">Issues</span></router-link>
                                </li>
                            </div>
                            <div class="col-auto pe-0">
                                <li class="nav-item">
                                    <a v-if="userStore.user" class="nav-link p-0" href="#"
                                        v-on:click.prevent="userStore.logoutUser()"><i
                                            class="d-sm-none fa-solid fa-xl fa-right-from-bracket fa-flip-horizontal"></i><span
                                            class="d-none d-sm-block fs-5">Log out</span></a>
                                    <router-link v-else class="nav-link p-0" activeClass="active"
                                        :to="{ name: 'login' }"><i
                                            class="d-sm-none fa-solid fa-xl fa-right-from-bracket"></i><span
                                            class="d-none d-sm-block fs-5">Log in</span></router-link>

                                </li>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

</template>