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
            console.log(paginationItem);
            if (!paginationItem) {
                return;
            }
            paginationItem.classList.add('is-active');
            sidebar.classList.add('has-active');
        })
    }
}

export default ShowPost;