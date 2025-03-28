Hi, here is a revised set of requirements booking approach for the zoo that you may want to consider. Amend them where you need to.
 
Requirements:
User details (e.g., name, email, contact number, login credentials).
The website must be developed in PHP, allowing user login checks before booking.
Users must be able to select the type of booking:
Animal Encounter
Exhibit Tour
Educational Talk
Users must also select the type of exhibit/animal associated with the booking, such as:
Big Cats
Reptiles
Birds
Aquatic Life
Safari Animals
After selecting booking type and category, the system should display the next 10 available booking slots, including the day and date.
Users must select one of the available booking slots.
Educational Talks and Exhibit Tours can be booked for either AM or PM slots.
Animal Encounters are all-day events.
Bookings for Animal Encounters and Exhibit Tours must be assigned a Zookeeper or Guide who specialises in the chosen animal/exhibit type.
A Zookeeper or Guide may be qualified to work with multiple exhibit/animal types.
A user can make multiple bookings, but may only have one active Animal Encounter per exhibit/animal type.
Each booking should include a status: active, completed or cancelled.
A Zookeeper or Guide may only have one booking per time slot (AM, PM, or all-day).
No bookings should be allowed for past dates.
Bookings must start from the next day onward.
Zookeeper/Guide details must be recorded, including:
Full name
Areas of expertise (exhibits/animal types)
Contact number
Days of availability



<!-- Elfsight Calculator | Untitled Calculator -->
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-45ac2dbe-14fd-4496-82c2-2974842c0e54" data-elfsight-app-lazy></div>
 
 
