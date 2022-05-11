<template>
  <div>
    <button
      type="button"
      class="btn shadow-none"
    >
      <i class="fas fa-heart fa-2x"
         :class="{'text-primary':this.isLikedBy}" 
         @click="Like" 
      />
    </button>
    {{ countLikes }} 
  </div>
</template>

<script>
  export default {
    props: {
      isLiked: {
        type: Boolean,
        default: false,
      },
      countLikes: {
        type: Number,
        default: 0,
      },
      authorized: {
        type: Boolean,
        default: false,
      },
      endpoint: {
        type: String,
      },
    },
    data() {
      return {
        isLikedBy: this.isLiked,
        countLikes: this.countLikes,
      }
    },
     methods: {
      Like() {
        this.isLikedBy
          ? this.unlike()
          : this.like()
      },
      async like() {
        const response = await axios.put(this.endpoint)

        this.isLikedBy = true
        this.countLikes = response.data.countLikes
      },
      async unlike() {
        const response = await axios.delete(this.endpoint)

        this.isLikedBy = false
        this.countLikes = response.data.countLikes
      },
    },
  }
</script>
