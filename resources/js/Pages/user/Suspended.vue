<template>
  <div id="main" class="d-flex align-items-center justify-content-center">
    <div class="card">
      <div class="card-header">
        <h3>Account suspended</h3>
      </div>
      <div class="card-body">
        <p>
          Sorry! Your account has been suspended! Please contact the exam office
          for further information!
        </p>
      </div>
      <div class="card-footer">
        <inertia-link
          class="btn btn-danger"
          as="button"
          method="post"
          :href="$route('logout')"
          >Logout</inertia-link
        >
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style scoped>
#main {
  height: 100vh;
}
</style>