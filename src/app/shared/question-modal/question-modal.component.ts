import { Component } from '@angular/core';
import { QuestionModalService } from './question-modal.service';
import { QuestionFormComponent } from '../question-form/question-form.component';

@Component({
  selector: 'app-question-modal',
  standalone: true,
  imports: [QuestionFormComponent],
  templateUrl: './question-modal.component.html',
  styleUrl: './question-modal.component.scss',
})
export class QuestionModalComponent {
  constructor(readonly modal: QuestionModalService) {}

  close(): void {
    this.modal.close();
  }

  onBackdropClick(event: MouseEvent): void {
    if (event.target === event.currentTarget) {
      this.close();
    }
  }
}
