<?php
/** @var array $tour */
/** @var array $commons */
/** @var string $currency */
/** @var string $bookingModalId */
$bookingDate = date('F j, Y', strtotime($tour['starts_at']));
?>
<div class="modal fade" id="<?php echo htmlspecialchars($bookingModalId, ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-labelledby="<?php echo htmlspecialchars($bookingModalId, ENT_QUOTES, 'UTF-8'); ?>-title" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable seasonal-tour-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title fs-5" id="<?php echo htmlspecialchars($bookingModalId, ENT_QUOTES, 'UTF-8'); ?>-title">Book <?php echo htmlspecialchars($tour['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p class="mb-1"><strong>Date:</strong> <?php echo htmlspecialchars($bookingDate, ENT_QUOTES, 'UTF-8'); ?></p>
				<p class="mb-4"><strong>Price:</strong> <?php echo htmlspecialchars(($commons['currencies'][$currency] ?? $currency) . number_format((float) $tour['price']), ENT_QUOTES, 'UTF-8'); ?> per person</p>
				<form id="booking-form-<?php echo htmlspecialchars((string) $tour['tour_slot_id'], ENT_QUOTES, 'UTF-8'); ?>" class="seasonal-booking-form" data-booking-form>
					<input type="hidden" name="tour_slot_id" value="<?php echo (int) $tour['tour_slot_id']; ?>">
					<div class="mb-3">
						<label class="form-label" for="booking-name-<?php echo (int) $tour['tour_slot_id']; ?>">Full name</label>
						<input class="form-control" id="booking-name-<?php echo (int) $tour['tour_slot_id']; ?>" name="customer_name" type="text" autocomplete="name" maxlength="255" required>
					</div>
					<div class="mb-3">
						<label class="form-label" for="booking-email-<?php echo (int) $tour['tour_slot_id']; ?>">Email</label>
						<input class="form-control" id="booking-email-<?php echo (int) $tour['tour_slot_id']; ?>" name="customer_email" type="email" autocomplete="email" maxlength="255" required>
					</div>
					<div class="mb-3">
						<label class="form-label" for="booking-whatsapp-<?php echo (int) $tour['tour_slot_id']; ?>">WhatsApp number</label>
						<input class="form-control" id="booking-whatsapp-<?php echo (int) $tour['tour_slot_id']; ?>" name="customer_whatsapp" type="tel" autocomplete="tel" maxlength="32" required>
					</div>
					<div class="mb-3">
						<label class="form-label" for="booking-guests-<?php echo (int) $tour['tour_slot_id']; ?>">Number of guests</label>
						<input class="form-control" id="booking-guests-<?php echo (int) $tour['tour_slot_id']; ?>" name="guests" type="number" min="1" max="<?php echo (int) $tour['available']; ?>" value="1" required>
					</div>
					<div class="mb-3">
						<label class="form-label" for="booking-requests-<?php echo (int) $tour['tour_slot_id']; ?>">Special requests <span class="text-muted">(optional)</span></label>
						<textarea class="form-control" id="booking-requests-<?php echo (int) $tour['tour_slot_id']; ?>" name="special_requests" rows="4" maxlength="2000"></textarea>
					</div>
					<p class="small text-danger mb-0" data-booking-error role="alert" hidden></p>
				</form>
				<div class="alert alert-success mb-0" data-booking-success role="status" hidden>
					Your booking details have been saved.<br>Continue to checkout to complete your reservation.<br>
                    Once completed, you will receive a confirmation email with your booking details and receipt.
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="cta-button info" data-bs-dismiss="modal">Close</button>
				<button class="cta-button" type="submit" form="booking-form-<?php echo (int) $tour['tour_slot_id']; ?>" data-booking-submit>Continue</button>
				<a class="cta-button" href="<?php echo htmlspecialchars($tour['paypal_payment_link'], ENT_QUOTES, 'UTF-8'); ?>" rel="nofollow noopener noreferrer" target="_blank" data-checkout-link hidden>Continue to checkout</a>
			</div>
		</div>
	</div>
</div>
