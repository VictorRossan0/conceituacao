<script setup>
defineProps({
  type: {
    type: String,
    default: 'success',
  },
  message: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <div class="alert" :class="`alert-${type}`" role="alert">
    {{ message }}
  </div>
</template>
