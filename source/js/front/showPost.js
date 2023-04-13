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
            if (!paginationItem) {
                return;
            }
            paginationItem.classList.add('is-active');
            paginationContainer.querySelectorAll('.openstreetmap__collection__item').forEach(item => {
                item.classList.add('u-display--none');
            })
        })
    }
}

export default ShowPost;