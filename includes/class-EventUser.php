<?php
# Linux Day 2016 - Construct a database event-user relaction
# Copyright (C) 2016 Valerio Bozzolan
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU Affero General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

trait EventUserTrait {
	function getEventUserOrder() {
		isset( $this->event_user_order )
			|| error_die("Missing event_user_order");

		return $this->event_user_order;
	}

	function deleteEventUser() {
		query( sprintf(
			"DELETE FROM {$GLOBALS[T]('event_user')} WHERE event_ID = %d AND USER_ID = %d",
			$this->getEventID(),
			$this->getUserID()
		) );
	}
}

class_exists('Event');
class_exists('User');

class EventUser {
	use EventUserTrait, EventTrait, UserTrait;

	function __construct() {
		self::normalize($this);
		Event::normalize($this);
		User::normalize($this);
	}

	static function normalize(& $t) {
		if( isset( $t->event_user_order ) ) {
			$t->event_user_order = (int) $t->event_user_order;
		}
	}
}
