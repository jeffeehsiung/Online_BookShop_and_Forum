import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['progressBar']

    setInitial(averageRating) {
        this.progressBarTarget.style.width = ((averageRating/5)*100) + "%"
    }
}