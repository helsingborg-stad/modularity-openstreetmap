class ShowPost {
    constructor() {
        this.container = document.querySelector('#openstreetmap');
        this.container && this.handleClick();
    }
    handleClick() {
        let paginationContainer = this.container.querySelector('[js-pagination-container]');
        
        paginationContainer.addEventListener('click', (e) => {
            let paginationItem = e.target.closest('[js-pagination-item]');
            console.log(paginationItem);
            if (paginationItem) {
                paginationItem.classList.add('is-active');
            }
        })
    }
}

export default ShowPost;