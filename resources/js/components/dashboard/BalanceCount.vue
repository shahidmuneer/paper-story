<template>
  <div class="card text-white bg-danger">
    <div class="card-body">
      <div class="h1 text-muted text-right mb-4">
       	

<!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
<svg version="1.1" id="Capa_1"
width="50"
height="50"
	xmlns="http://www.w3.org/2000/svg"
	xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
	<path style="fill:#FFB64F;" d="M512,256c0,68.38-26.63,132.67-74.98,181.02S324.39,512,256.01,512H256
	c-68.38,0-132.67-26.63-181.02-74.98S0,324.38,0,256S26.63,123.33,74.98,74.98S187.62,0,256,0h0.01
	c68.38,0,132.66,26.63,181.01,74.98S512,187.62,512,256z"/>
	<g>
		<path style="fill:#FF9900;" d="M512,256c0,68.38-26.63,132.67-74.98,181.02S324.39,512,256.01,512V0
		c68.38,0,132.66,26.63,181.01,74.98S512,187.62,512,256z"/>
		<path style="fill:#FF9900;" d="M449.39,256c0,106.64-86.74,193.38-193.38,193.39H256c-106.64,0-193.39-86.75-193.39-193.39
		S149.36,62.61,256,62.61h0.01C362.65,62.62,449.39,149.36,449.39,256z"/>
	</g>
	<path style="fill:#E57500;" d="M449.39,256c0,106.64-86.74,193.38-193.38,193.39V62.61C362.65,62.62,449.39,149.36,449.39,256z"/>
	<path style="fill:#FFDAA8;" d="M312.42,300.23c0,26.09-17.31,48.21-41.04,55.49v37.18h-30v-34.64h-31.15v-30h44.16
	c0.54,0,1.09-0.02,1.62-0.05c14.71-0.84,26.41-13.06,26.41-27.98s-11.7-27.15-26.41-27.99c-0.53-0.03-1.08-0.05-1.62-0.05
	c-30.23,0-54.82-24.59-54.82-54.81v-5.93c0-25.74,17.84-47.38,41.81-53.24V119.1h30v37.54h30.39v30h-47.38
	c-13.68,0-24.82,11.13-24.82,24.81v5.93c0,13.68,11.14,24.81,24.82,24.81c0.54,0,1.08,0.01,1.62,0.02
	C287.26,243.07,312.42,268.77,312.42,300.23z"/>
	<g>
		<path style="fill:#FFC373;" d="M312.42,300.23c0,26.09-17.31,48.21-41.04,55.49v37.18h-15.37v-64.69
		c14.71-0.84,26.41-13.06,26.41-27.98s-11.7-27.15-26.41-27.99v-30.03C287.26,243.07,312.42,268.77,312.42,300.23z"/>
		<polygon style="fill:#FFC373;" points="301.77,186.64 256.01,186.64 256.01,119.1 271.38,119.1 271.38,156.64 301.77,156.64 	"/>
	</g>
	<g></g>
	<g></g>
	<g></g>
	<g></g>
	<g></g>
	<g></g>
	<g></g>
	<g></g>
	<g></g>
	<g></g>
	<g></g>
	<g></g>
	<g></g>
	<g></g>
	<g></g>
</svg>

      </div>
      <div class="text-value">{{balance}}</div>
      <small class="text-muted text-uppercase font-weight-bold">Total Balance</small>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      balance: 0
    }
  },
  mounted () {
    this.getRolesCount()
  },
  methods: {
    getRolesCount () {
      axios.get(`/api/users/balance/count`)
      .then(response => {
        this.balance = response.data
      })
    },
  }
}
</script>
