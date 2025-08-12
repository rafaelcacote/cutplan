<template>
  <span :class="[
    'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold',
    ativo ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-gray-200 text-gray-600 border border-gray-300'
  ]">
    <span class="w-2 h-2 rounded-full mr-2" :class="ativo ? 'bg-green-500' : 'bg-gray-400'"></span>
    <slot />
  </span>
</template>
<script setup lang="ts">
defineProps<{ ativo: boolean }>()
</script>
