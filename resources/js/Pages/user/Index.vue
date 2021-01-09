<template>
  <div>
    <h1>Hello, {{ user.name }}</h1>
    <inertia-link as="button" method="post" href="/logout">Logout</inertia-link>
  </div>
</template>

<script>
export default {
  props: ["user"],
};
</script>

<style>
</style>