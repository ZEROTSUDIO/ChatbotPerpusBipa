// Dashboard JavaScript
$(document).ready(function () {
	/* Sidebar toggle functionality
	$("#sidebar-toggle").click(function () {
		const sidebar = $("#sidebar");
		const mainWrapper = $("#main-wrapper");

		if (window.innerWidth <= 768) {
			// Mobile behavior
			sidebar.toggleClass("mobile-open");
			$("#mobile-backdrop").toggleClass("show");
		} else {
			// Desktop behavior
			sidebar.toggleClass("collapsed");
			if (sidebar.hasClass("collapsed")) {
				mainWrapper.removeClass("sidebar-open").addClass("sidebar-collapsed");
			} else {
				mainWrapper.removeClass("sidebar-collapsed").addClass("sidebar-open");
			}
		}
	});

	// Mobile backdrop click
	$("#mobile-backdrop").click(function () {
		$("#sidebar").removeClass("mobile-open");
		$(this).removeClass("show");
	});

	// Sidebar navigation
	$(".sidebar-item").click(function (e) {
		e.preventDefault();

		// Remove active class from all items
		$(".sidebar-item").removeClass("active");
		// Add active class to clicked item
		$(this).addClass("active");

		// Hide all content sections
		$(".content-section").addClass("hidden");

		// Show selected section
		const section = $(this).data("section");
		$(`#${section}-content`).removeClass("hidden");

		// Update breadcrumb
		const sectionName = $(this).find(".sidebar-text").text();
		$("#current-section").text(sectionName);

		// Close mobile sidebar
		if (window.innerWidth <= 768) {
			$("#sidebar").removeClass("mobile-open");
			$("#mobile-backdrop").removeClass("show");
		}
	});
	*/

	// Initialize charts
	initializeCharts();
});

function initializeCharts() {
	// Chat Activity Chart
	const chatCtx = document.getElementById("chatActivityChart").getContext("2d");
	new Chart(chatCtx, {
		type: "line",
		data: {
			labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
			datasets: [
				{
					label: "Chat Sessions",
					data: [65, 59, 80, 81, 56, 55, 40],
					borderColor: "#667eea",
					backgroundColor: "rgba(102, 126, 234, 0.1)",
					borderWidth: 3,
					fill: true,
				},
			],
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					display: false,
				},
			},
			scales: {
				y: {
					beginAtZero: true,
				},
			},
		},
	});

	// User Engagement Chart
	const engagementCtx = document
		.getElementById("userEngagementChart")
		.getContext("2d");
	new Chart(engagementCtx, {
		type: "doughnut",
		data: {
			labels: ["Active Users", "Returning Users", "New Users"],
			datasets: [
				{
					data: [45, 35, 20],
					backgroundColor: ["#11998e", "#f5576c", "#4facfe"],
					borderWidth: 2,
					borderColor: "#fff",
				},
			],
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					position: "bottom",
				},
			},
		},
	});
}

// Handle window resize
window.addEventListener("resize", function () {
	const sidebar = $("#sidebar");
	const mainWrapper = $("#main-wrapper");

	if (window.innerWidth > 768) {
		sidebar.removeClass("mobile-open");
		$("#mobile-backdrop").removeClass("show");

		if (sidebar.hasClass("collapsed")) {
			mainWrapper.removeClass("sidebar-open").addClass("sidebar-collapsed");
		} else {
			mainWrapper.removeClass("sidebar-collapsed").addClass("sidebar-open");
		}
	} else {
		mainWrapper.removeClass("sidebar-open sidebar-collapsed");
	}
});
