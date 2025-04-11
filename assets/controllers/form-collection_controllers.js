import { Controller } from '@hotwired/stimulus';
export default class extends Controller {
    static targets = ["collectionContainer"]

    static values = {
        index : Number,
        prototype: String,
    }

    addCollectionElement(event)
    {
        const item = document.createElement('li');
        item.innerHTML = this.prototypeValue.replace(/__name__/g,
this.indexValue);
        this.collectionContainerTarget.appendChild(item);
        this.indexValue++;
        addTagFormDeleteLink(item);

    }
}
const addTagFormDeleteLink = (item) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerText = 'Supprimez-le';

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });
}