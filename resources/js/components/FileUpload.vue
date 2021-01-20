<template>
  <div id="profile-pic-demo" class="pt-3">
    <VueFileAgent
      class="profile-pic-upload-block"
      ref="fileUploader"
      :multiple="false"
      :deletable="false"
      :meta="false"
      :compact="true"
      :accept="'image/*, .pdf'"
      :helpText="'Drag an image / pdf file here'"
      :maxSize="'5MB'"
      :errorText="{
        type: 'Please select an image / pdf',
        size: 'Files should not exceed 5MB in size',
      }"
      v-model="file"
      @select="onSelect($event)"
    >
      <template v-slot:before-outer> </template>
      <template v-slot:after-inner>
        <span title="after-inner" class="btn btn-link btn-sm btn-block"
          >Select image file</span
        >
      </template>
      <template v-slot:after-outer>
        <div title="after-outer">
          <p class="form-control-label">
            {{ item.label }}
          </p>
          <p class="small">{{ item.description }}</p>
          <p class="small"><b>Supported: </b><span>Images, PDF</span></p>
          <p class="small"><b>Max.Size: </b><span>5MB</span></p>
          <div class="drop-help-text">
            <p class="text-success">Drop the file!</p>
          </div>
          <base-button
            type="primary"
            :class="{ disabled: uploaded || !file }"
            @click="upload()"
          >
            Upload
          </base-button>
          <base-button
            :type="uploaded ? 'danger' : 'secondary'"
            v-if="file"
            @click="removePic()"
          >
            Remove
          </base-button>
          <div class="clearfix"></div>
        </div>
      </template>
    </VueFileAgent>
  </div>
</template>

<script>
export default {
  props: ["item", "uploadUrl", "deleteUrl"],
  data() {
    return {
      file: null,
      uploaded: false,
      uploadHeaders: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]')[0].content,
      },
    };
  },
  methods: {
    removePic() {
      axios
        .post(this.$route("user.deleteUpload"), {
          item_user_id: this.item.pivot.id,
        })
        .then(() => {
          this.file = null;
          this.uploaded = false;
        });
    },
    upload() {
      if (!this.uploaded) {
        var self = this;
        this.$refs.fileUploader
          .upload(
            this.uploadUrl,
            this.uploadHeaders,
            [this.file],
            function createFormData(fileData) {
              var formData = new FormData();
              formData.append("id", self.item.id);
              formData.append("file", fileData.file);
              formData.append("ext", fileData.ext());
              formData.append("size", fileData.size());
              return formData;
            }
          )
          .then(function () {
            self.uploaded = true;
          });
      }
    },
    onSelect(fileRecords) {
      console.log("onSelect", fileRecords);
      this.uploaded = false;
    },
  },
  created() {
    if (this.item.pivot) {
      const item = JSON.parse(this.item.pivot.value_info);

      if (item) {
        this.file = {
          name: item.name,
          type: item.type,
          url: item.url,
          ext: item.ext,
          size: item.size,
          sizeText: item.sizeText,
        };

        this.uploaded = true;
      }
      // this.file = {
      //   name: this.oldValue.name,
      //   url: this.oldValue.url,
      //   ext: this.oldValue.ext,
      //   sizeText: this.oldValue.sizeText,
      // };
    }
  },
};
</script>

<style>
#profile-pic-demo .drop-help-text {
  display: none;
}
#profile-pic-demo .is-drag-over .drop-help-text {
  display: block;
}
#profile-pic-demo .profile-pic-upload-block {
  border: 2px dashed transparent;
  padding: 20px;
  padding-top: 0;
}

#profile-pic-demo .is-drag-over.profile-pic-upload-block {
  border-color: #aaa;
}
#profile-pic-demo .vue-file-agent {
  width: 180px;
  float: left;
  margin: 0 15px 5px 0;
  border: 0;
  box-shadow: none;
}

@media (max-width: 568px) {
  #profile-pic-demo .vue-file-agent {
    float: none;
    width: 100%;
  }
}
</style>