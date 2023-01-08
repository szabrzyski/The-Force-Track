<script setup>

const props = defineProps({
    issues: Object,
});

const emit = defineEmits(['paginateIssues']);

</script>

<template>

    <div class="row mt-3">
        <div class="col-12 ">
            <nav>

                <div class="row d-sm-none">
                    <div class="col-12">
                        <div class="btn-group w-100">
                            <a class="btn btn-secondary bg-secondary border-secondary w-50"
                                :class="{ disabled: props.issues.current_page == 1 }"
                                v-on:click="emit('paginateIssues', props.issues.current_page - 1, true)"
                                rel="prev">Previous</a>
                            <a class="btn btn-success w-50"
                                :class="{ disabled: props.issues.current_page == props.issues.last_page }"
                                v-on:click="emit('paginateIssues', props.issues.current_page + 1, true)"
                                rel="next">Next</a>
                        </div>
                    </div>
                </div>

                <div class="d-none d-sm-block">
                    <ul class="pagination justify-content-end m-0 d-flex flex-wrap">

                        <!-- Previous Page Link -->

                        <li class="page-item" :class="{ disabled: props.issues.current_page == 1 }">
                            <a class="page-link"
                                v-on:click="emit('paginateIssues', props.issues.current_page - 1, true)"
                                rel="prev">&lsaquo;</a>
                        </li>

                        <!-- Pagination Elements -->

                        <template v-for="(link, index) in props.issues.links" :key="index">

                            <!-- "Three Dots" Separator -->

                            <li v-if="link.label == '...'" class="page-item disabled"><span class="page-link">{{
                                link.label
                            }}</span></li>

                            <li v-if="link.label >= 0 && link.label == props.issues.current_page"
                                class="page-item active"><span class="page-link bg-success">{{
                                    link.label
                                }}</span></li>
                            <li v-else-if="link.label >= 0 && link.label != props.issues.current_page"
                                class="page-item">
                                <a class="page-link pointer" v-on:click="emit('paginateIssues', link.label, true)">{{
                                    link.label
                                }}</a>
                            </li>

                        </template>

                        <!-- Next Page Link -->

                        <li class="page-item"
                            :class="{ disabled: props.issues.current_page == props.issues.last_page }">
                            <a class="page-link"
                                v-on:click="emit('paginateIssues', props.issues.current_page + 1, true)"
                                rel="next">&rsaquo;</a>
                        </li>

                    </ul>
                </div>
            </nav>
        </div>
    </div>

</template>