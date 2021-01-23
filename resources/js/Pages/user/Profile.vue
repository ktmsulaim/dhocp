<template>
  <div>
    <base-header
      type="gradient-success"
      class="pb-6 pb-8 pt-5 pt-md-8"
    ></base-header>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <div class="card-header">
              <h3>Profile</h3>
            </div>
            <div class="card-body">
              <div class="container">
                <div class="row my-3">
                  <div class="col col-md-4 text-center">
                    <div class="profile">
                      <img
                        :src="profilePicture"
                        alt="User photo"
                        height="150"
                        style="max-width='100%'"
                      />
                      <div class="my-2">
                        <small
                          style="cursor: pointer"
                          @click="modals.profile = !modals.profile"
                          >Update photo</small
                        >
                      </div>
                    </div>
                    <div class="mt-2 mb-4 mb-md-0">
                      <h4>{{ user.data.name }}</h4>
                    </div>
                  </div>
                  <div class="col col-md-8">
                    <div class="table-responsive">
                      <table class="table border">
                        <tr>
                          <th width="100">Batch</th>
                          <td>{{ user.data.batch.name }}</td>
                        </tr>
                        <tr>
                          <th width="100">Enroll no</th>
                          <td>{{ user.data.enroll_no }}</td>
                        </tr>
                        <tr>
                          <th width="100">DOB</th>
                          <td>{{ user.data.dob_formatted }}</td>
                        </tr>
                        <tr>
                          <th width="100">Status</th>
                          <td>
                            <badge v-if="user.data.active == 1" type="success"
                              >Active</badge
                            >
                            <badge v-else type="danger">Suspended</badge>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <modal :show.sync="modals.profile">
                      <template slot="header">
                        <h5 class="modal-title" id="exampleModalLabel">
                          Upload photo
                        </h5>
                      </template>
                      <div>
                        <div id="cropper-container" class="my-2">
                          <vue-cropper
                            v-if="photo.src"
                            ref="cropper"
                            :imgStyle="{ height: '300px' }"
                            :containerStyle="{ maxHeight: '300px' }"
                            :aspect-ratio="3 / 4"
                            :src="photo.src"
                            :cropBoxResizable="false"
                            :viewMode="2"
                          />
                        </div>
                        <b-form-file
                          v-model="photo.file"
                          :state="Boolean(photo.file)"
                          placeholder="Choose a file or drop it here..."
                          drop-placeholder="Drop file here..."
                          accept="image/*"
                          @change="setImage"
                        ></b-form-file>
                      </div>
                      <template slot="footer">
                        <base-button
                          type="secondary"
                          @click="modals.profile = false"
                          >Close</base-button
                        >
                        <base-button
                          :loading="loading"
                          :disabled="loading"
                          @click="submit"
                          type="primary"
                          >Save changes</base-button
                        >
                      </template>
                    </modal>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardLayout from "../../layout/users/DashboardLayout";
import VueCropper from "vue-cropperjs";
import "cropperjs/dist/cropper.css";

export default {
  layout: DashboardLayout,
  props: ["user"],
  components: {
    VueCropper,
  },
  data() {
    return {
      modals: {
        profile: false,
      },
      photo: {
        file: null,
        src: null,
        cropped: null,
      },
      profilePicture: null,
      loading: false,
    };
  },
  methods: {
    setImage(e) {
      const file = e.target.files[0];
      if (file.type.indexOf("image/") === -1) {
        alert("Please select an image file");
        return;
      }
      if (typeof FileReader === "function") {
        const reader = new FileReader();
        reader.onload = (event) => {
          this.photo.src = event.target.result;
          // rebuild cropperjs with the updated source
        };
        reader.readAsDataURL(file);

        if (this.photo.src) {
          this.$refs.cropper.replace(this.photo.src);
        }
      } else {
        alert("Sorry, FileReader API not supported");
      }
    },
    cropImage() {
      // get image data for post processing, e.g. upload or setting image src
      this.photo.cropped = this.$refs.cropper.getCroppedCanvas().toDataURL();
    },
    submit() {
      this.cropImage();
      if (this.photo.cropped) {
        this.loading = true;

        axios
          .post(this.$route("user.profile.update"), {
            photo: this.photo.cropped,
          })
          .then((resp) => {
            this.profilePicture = this.photo.cropped;

            this.$refs.cropper.reset();
            this.photo.src = null;
            this.photo.file = null;
            this.photo.cropped = null;

            this.modals.profile = false;
          })
          .catch((err) => console.log("Unable to update the profile!"))
          .finally(() => {
            this.loading = false;
          });
      }
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Profile");

    if (this.user && this.user.data.profile) {
      this.profilePicture = this.user.data.profile;
    }
  },
};
</script>

<style scoped>
#cropper-container {
  max-height: 300px;
  max-width: 100%;
}
</style>