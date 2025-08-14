<script setup lang="ts">
import Create from './Create.vue';

interface Props {
    orcamento: any;
}

defineProps<Props>();
</script>

<template>
    <Create :orcamento="orcamento" />
</template>
