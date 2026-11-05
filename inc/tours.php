<?php
$commons = $commons ?? json_decode(file_get_contents(__DIR__ . '/../../shared/commons.json'), true);
if (isset($tours)): ?>
    <section class="tours tours__seasonal">
        <div class="container">
            <div class="row">
                <?php foreach ($tours as $tourIndex => $tour): ?>
                    <?php $currency = $tour['currency']; ?>
                    <?php $modalId = 'tour-info-' . ($tour['tour_slot_id'] ?? $tourIndex); ?>
                    <div class="col-md-4 pt-5 pb-5 tour-block mt-5 mt-md-0 mb-5 mb-md-0">
                        <h3 class="font-black"><?php echo $tour['name']; ?></h3>
                        <div class="seperator"></div>
                        <ul>
                            <?php if (isset($tour['description'])): ?>
                                <li>
                                    <i class="fi-rr-location-alt align-middle"></i> <?php echo $tour['description']; ?>
                                </li>
                            <?php endif; ?>
                            <li><i class="fi-rr-calendar bold align-middle"></i> <?php echo date('F j, Y', strtotime($tour['starts_at'])); ?></li>
                            <li><i class="fi-rr-clock bold align-middle"></i> <?php echo $tour['duration_minutes'] / 60; ?> Hours</li>
                            <li><i class="fi-rr-users bold align-middle"></i> Max <?php print $tour['capacity']; ?> guests</li>
                            <li><i class="fi-rr-car bold align-middle"></i> <?php echo $tour['vehicle_type']; ?> + <?php print $tour['guide_language'] . ' Guide'; ?></li>
                            <li><i class="fi-rr-user-check bold align-middle"></i> <?php echo $tour['available']; ?>/<?php echo $tour['capacity']; ?></li>
                            <li><i class="fi-rr-money-bill-wave bold align-middle"></i> <span class="font-bold"><?php echo $commons['currencies'][$currency] . $tour['price']; ?></span>/ person</li>
                        </ul>
                        <div class="row mt-4">
                            <div class="col-6">
                                <a class="cta-button" href="<?php echo $tour['paypal_payment_link']; ?>" rel="nofollow noopener noreferrer" target="_blank">
                                    Book Now
                                </a>
                            </div>
                            <div class="col-6">
                                <button class="cta-button info" type="button" data-bs-toggle="modal" data-bs-target="#<?php echo $modalId; ?>">
                                    More Info
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1" aria-labelledby="<?php echo $modalId; ?>-title" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable seasonal-tour-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title fs-5" id="<?php echo $modalId; ?>-title"><?php echo htmlspecialchars($tour['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?php $tourDescription = $tour['long_description'] ?: $tour['description']; ?>
                                    <?php if ($tourDescription): ?>
                                        <p><?php echo nl2br(htmlspecialchars($tourDescription, ENT_QUOTES, 'UTF-8')); ?></p>
                                    <?php endif; ?>

                                    <p class="mb-1">
                                        <i class="fi-rr-calendar bold align-middle"></i> <?php echo htmlspecialchars(date('F j, Y', strtotime($tour['starts_at'])), ENT_QUOTES, 'UTF-8'); ?>
                                    </p>
                                    <p class="mb-1">
                                        <i class="fi-rr-clock bold align-middle"></i> <?php echo htmlspecialchars((string) ($tour['duration_minutes'] / 60), ENT_QUOTES, 'UTF-8'); ?> Hours
                                        &bull; <i class="fi-rr-users bold align-middle"></i> Up to <?php echo htmlspecialchars((string) $tour['capacity'], ENT_QUOTES, 'UTF-8'); ?> guests
                                        &bull; <i class="fi-rr-car bold align-middle"></i> <?php echo htmlspecialchars($tour['vehicle_type'] . ' + ' . $tour['guide_language'] . ' Guide', ENT_QUOTES, 'UTF-8'); ?>
                                    </p>
                                    <p class="fw-bold mb-0"><?php echo htmlspecialchars(($commons['currencies'][$currency] ?? $currency) . number_format((float) $tour['price']), ENT_QUOTES, 'UTF-8'); ?> / person</p>

                                    <hr>
                                    <h3 class="h5 font-black">Meeting &amp; Drop-Off</h3>
                                    <p class="mb-1"><strong>Meeting Point:</strong> <?php echo htmlspecialchars($tour['meeting_point'] ?: 'Details to be announced', ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p class="mb-1"><strong>Meet:</strong> <?php echo htmlspecialchars($tour['meeting_time'] ?: 'Details to be announced', ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p class="mb-1"><strong>Departure:</strong> <?php echo htmlspecialchars($tour['departure_time'] ?: 'Details to be announced', ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p>Please arrive on time. Late arrivals may not be accommodated because the tour follows a planned schedule.</p>
                                    <p class="mb-0"><strong>Drop-off:</strong> <?php echo htmlspecialchars($tour['dropoff_point'] ?: 'Details to be announced', ENT_QUOTES, 'UTF-8'); ?></p>

                                    <hr>
                                    <h3 class="h5 font-black">Itinerary</h3>
                                    <?php $itinerary = $itinerariesByTour[$tour['id']] ?? []; ?>
                                    <?php if ($itinerary): ?>
                                        <?php foreach ($itinerary as $item): ?>
                                            <div class="mb-3">
                                                <?php if (!empty($item['time'])): ?>
                                                    <p class="h6 fw-bold mb-1"><?php echo htmlspecialchars($item['time'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                <?php endif; ?>
                                                <h4 class="h6 fw-bold"><?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                                <?php if (!empty($item['description'])): ?>
                                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8')); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                        <!-- General Info -->
                                        <p class="opacity-75 font-m">
                                            The exact timing may change slightly depending on traffic, weather, and time spent at each location.
                                        </p>
                                        <div class="border-top pt-3 pb-3">
                                            <h4 class="font-bold">
                                                What's Included
                                            </h4>
                                            <div class="seperator"></div>
                                            <div class="row font-m">
                                                <div class="col-6"><i class="fi-rr-check align-middle checkmark-icon"></i> English-speaking local guide</div>
                                                <div class="col-6"><i class="fi-rr-check align-middle checkmark-icon"></i> Professional driver</div>
                                                <div class="col-6"><i class="fi-rr-check align-middle checkmark-icon"></i> HiAce van</div>
                                                <div class="col-6"><i class="fi-rr-check align-middle checkmark-icon"></i> 1 local snack</div>
                                                <div class="col-6"><i class="fi-rr-check align-middle checkmark-icon"></i> Bottled water</div>
                                                <div class="col-6"><i class="fi-rr-check align-middle checkmark-icon"></i> Deer crackers</div>
                                                <div class="col-6"><i class="fi-rr-check align-middle checkmark-icon"></i> Shrine & temple entrance fees</div>
                                                <div class="col-6"><i class="fi-rr-check align-middle checkmark-icon"></i> Gas</div>
                                                <div class="col-6"><i class="fi-rr-check align-middle checkmark-icon"></i> Toll fees</div>
                                                <div class="col-6"><i class="fi-rr-check align-middle checkmark-icon"></i> Parking fees</div>
                                                <div class="col-6"><i class="fi-rr-check align-middle checkmark-icon"></i> 10-hour tour</div>
                                            </div>
                                        </div>
                                        <div class="border-top pt-3 pb-3 font-m">
                                            <h4 class="font-bold">
                                                <i class="fi-rr-lightbulb-exclamation align-middle"></i> Please Note
                                            </h4>
                                            <ul>
                                                <li>This is an active walking tour. Comfortable walking shoes are recommended.</li>
                                                <li>This tour is not wheelchair accessible.</li>
                                                <li>The local snack is optional. If you have allergies or dietary restrictions, you do not need to try it.</li>
                                                <li>Children and adults are the same price, as every guest requires a seat in the vehicle.</li>
                                                <li>Please bring extra cash for lunch, additional food/drinks, shopping, and personal expenses.</li>
                                                <li>Please arrive on time. The group will depart at 8:00 AM sharp.</li>
                                            </ul>
                                        </div>
                                        <div class="bg-light p-3 rounded">
                                            <h6>
                                                Cancelation Policy
                                            </h6>
                                            <p class="font-s">
                                                Because this is a shared group tour, each reservation affects the number of spaces available for other guests.
                                                <strong>After booking, cancellations are non-refundable.</strong><br>
                                                If the tour is cancelled because the minimum number of guests is not reached, you will receive a <strong>100% refund.</strong><br>
                                                If <strong>severe weather</strong> makes the tour unsafe or impractical to operate, we will contact the group. If the tour needs to be cancelled, you will receive a <strong>full refund.</strong>
                                            </p>
                                        </div>
                                    <?php else: ?>
                                        <p class="mb-0">Itinerary details will be announced soon.</p>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="cta-button info" data-bs-dismiss="modal">Close</button>
                                    <a class="cta-button" href="<?php echo $tour['paypal_payment_link']; ?>" rel="nofollow noopener noreferrer" target="_blank">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>