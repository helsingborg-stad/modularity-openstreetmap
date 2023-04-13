class ShowPost {
    constructor() {
        this.container = document.querySelector('#openstreetmap');
        this.container && this.handleClick();
    }
    handleClick() {
        let paginationContainer = this.container.querySelector('[js-pagination-container]');
        let sidebar = this.container.querySelector('.openstreetmap__sidebar');
        
        paginationContainer.addEventListener('click', (e) => {
            let paginationItem = e.target.closest('[js-pagination-item]');
            let backButton = e.target.closest('.openstreetmap__post-icon');
            if (paginationItem) {
                paginationItem.classList.add('is-active');
                sidebar.classList.add('has-active');
            }

            if (backButton) {
                sidebar.classList.remove('has-active');
                sidebar.querySelectorAll('[js-pagination-item]').forEach(item => {
                    item.classList.remove('is-active');
                });
            }
        })
    }
}

export default ShowPost;