<?php
	class calendar {
	
		private $months = array(
			'January' 	=> 	31, 
			'February' 	=> 	28, 
			'March' 	=> 	31, 
			'April' 	=> 	30, 
			'May' 		=> 	31, 
			'June'	 	=> 	30, 
			'July' 		=> 	31, 
			'August' 	=> 	31, 
			'September'	=>	30, 
			'October' 	=> 	31, 
			'November' 	=> 	30, 
			'December' 	=> 	31
		);

		private $month;
		private $day;
		private $curMonth;
		private $curDay;
		
		public function __construct() {
			$this->month = (isset($_GET['month']) ? $_GET['month'] : date('F'));
			$this->day = (isset($_GET['day']) ? $_GET['day'] : date('j'));
			$this->curMonth = date('F');
			$this->curDay = date('j');
			//$this->isLeap();
			$this->makeFiles();
			$this->saveReminder();
			$this->deleteReminder();
		}
		
		private function makeFiles() {
			foreach($this->months as $month => $day) {
				$path = "data\\$month";
				if (!file_exists($path)) {
					mkdir($path, 0777, TRUE);
				}
				for ($i=1;$i<=$day;$i++) {
					if (!file_exists($path."\\".$i.".txt")) {
						touch($path."\\".$i.".txt");
					}
				}
			}
		}
		
		/*
		private function isLeap() {
			if (date('L') === 1)) { $this->months['February'] = 29; }
		}
		*/
		
		private function getNumDays() {
			$days = $this->months[$this->month];
			return range(1, $days);
		}
		
		private function getNumReminders($day) {
			$reminders = file("data\\$this->month\\$day.txt");
			return count($reminders);
		}
		
		private function saveReminder() {
			if (array_key_exists('day', $_POST)) {
				if (array_key_exists('month', $_POST)) {
					if (array_key_exists('reminder', $_POST)) {
						$reminders = file("data\\".$_POST['month']."\\".$_POST['day'].".txt");
						$reminders[] = $_POST['reminder']."\n";
						file_put_contents("data\\".$_POST['month']."\\".$_POST['day'].".txt", $reminders);
						header("Location: index.php?month=".$_POST['month']."&day=".$_POST['day']);
					}
				}
			}
		}
		
		private function deleteReminder() {
			if (array_key_exists('day', $_GET)) {
				if (array_key_exists('month', $_GET)) {
					if (array_key_exists('delete', $_GET)) {
						if (file_exists("data\\".$_GET['month']."\\".$_GET['day'].".txt")) {
							$reminders = file("data\\".$_GET['month']."\\".$_GET['day'].".txt");
							unset ($reminders[$_GET['delete']]);
							$reminders = array_values($reminders);
							file_put_contents("data\\".$_GET['month']."\\".$_GET['day'].".txt", $reminders);
						}
					}
				}
			}
		}
		
		private function printReminders() {
			$reminders = file("data\\$this->month\\$this->day.txt");
			echo "<div id='reminders'>";
			echo "<h2>Reminders for <a href='index.php?month=$this->month'>$this->month</a> $this->day</h2>";
			$this->printForm();
			echo "<ul>";
			foreach($reminders as $key => $reminder) {
				echo "<li>$reminder - <a href='index.php?month=$this->month&day=$this->day&delete=$key'>Delete</a></li>";
			}
			echo "</ul>";
			echo "</div>";
		}
		
		private function printCalendar() {
			echo "<table>";
			echo "<tr>";
			echo "<td colspan='10'>".$this->month."</td>";
			echo "</tr>";
			echo "<tr>";
				foreach($this->getNumDays() as $day) {
					$count = $this->getNumReminders($day);
					echo "<td><a href='index.php?month=$this->month&day=$day'>";
					echo (($day == $this->curDay && $this->month == $this->curMonth) ? "<span class='current'>$day</span>" : $day);
					echo "</a><span class='count'>$count</span></td>";
					
					if (($day % 10) == 0) {
						echo "</tr><tr>";
					}
				}
			echo "</tr>";
			echo "</table>";
		}
		
		private function printForm() {
			echo "<form action='".$_SERVER['PHP_SELF']."' method='POST' />";
			echo "<input type='text' value='Enter New Reminder' name='reminder' />";
			echo "<input type='hidden' value='$this->month' name='month' />";
			echo "<input type='hidden' value='$this->day' name='day' />";
			echo "<input type='submit' />";
			echo "</form>";
		}
		
		public function printMonths() {
			echo "<ul>";
				foreach($this->months as $month => $day) {
					if ($month == $this->curMonth) {
						echo "<li class='current'><a href='index.php?month=$month'>$month</a></li>";
					} else {
						echo "<li><a href='index.php?month=$month'>$month</a></li>";
					}
				}
			echo "</ul>";
		}
		
		public function getPage() {
		if (array_key_exists('day', $_GET)) {
					$this->printReminders();
				} else {
					$this->printCalendar();
				}
		}
		
		public function __destruct() {
			unset($this->months);
			unset($this->month);
			unset($this->day);
			unset($this->curMonth);
			unset($this->curDay);
		}
	}
?>
