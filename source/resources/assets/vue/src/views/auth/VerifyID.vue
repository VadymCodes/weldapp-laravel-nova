<template>
  <main>
    <NavigationBar />
    <form>
      <div class="flex flex-row items-end py-4 px-6 bg-gray-100">
        <label class="block flex-1">
          <span class="text-gray-700">Document Type</span>
          <select
            class="form-select mt-1 block w-full"
            :class="{ 'border-red-700': validationErrors.type || hasError }"
            v-model="type">
            <option selected value="">Select Type</option>
            <option value="passport">Passport</option>
            <option value="driver_license">Driver License</option>
          </select>
          <p v-if="validationErrors.type" class="text-red-700 ml-2 mb-2">
            {{ validationErrors.type[0] }}
          </p>
        </label>
      </div>
      <div class="flex flex-row items-end py-4 px-6 bg-gray-100">
        <label class="block flex-1">
          <span class="text-gray-700">Upload Document</span>
          <input type="file"
            class="form-select block w-full"
            :class="{ 'border-red-700': validationErrors.image || hasError }"
            @change="onFileChange">
          <p v-if="validationErrors.image" class="text-red-700 ml-2 mb-2">
            {{ validationErrors.image[0] }}
          </p>
        </label>
      </div>
      <div class="text-center">
        <button class="bg-royal-blue p-4 mb-20 text-white w-1/2 font-bold rounded-md mt-4 disabled:opacity-50 disabled:pointer-events-none"
          @click="submit"
          :disabled="isSubmitting">
          Upload
        </button>
      </div>
    </form>
  </main>
</template>

<script>
import axios from 'axios';
import NavigationBar from '@/components/global/NavigationBar.vue';

export default {
  components: {
    NavigationBar,
  },
  data() {
    return {
      isSubmitting: false,
      type: '',
      file: null
    }
  },
  computed: {
    hasError() {
      return this.$store.getters['hasError'];
    },
    validationErrors() {
      return this.$store.getters['validationErrors'];
    }
  },
  methods: {
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length)
        return;
      // this.createImage(files[0]);
      this.file = files[0];
    },
    submit() {
      this.isSubmitting = true;
      const that = this;

      var formData = new FormData();
      formData.append("type", this.type);
      formData.append("image", this.file);
      formData.append('_method', 'POST');
      axios.post('/api/v1/upload-id', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then((response) => {
          // if(response.data.error){
          //     // this.error = response.data.error;
          // } else {
              this.$router.replace({ name: 'home' });
              this.$toast.success('File is uploaded successfully');
              setTimeout(() => {
                that.$store.dispatch('auth/user');
              }, 5000);
          // }
        })
        .catch((err) => {
          if(err.response.data.message){
              // this.error = err.response.data.error;
              // this.$toast.error(this.$store.getters['error']);
              this.$toast.error(err.response.data.message);
          } else {
            this.$toast.error("We couldn't upload the image");
          }
          this.isSubmitting = false;
        });
    }
  }

}
</script>