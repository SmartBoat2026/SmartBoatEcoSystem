;
<?php $__env->startSection('content'); ?>;

<!-- ========== MAIN CONTENT ========== -->
    <main class="main" id="main" role="main">
               <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h1>Dashboard</h1>
                <p>Welcome back, Miller! Here's what's happening with your projects today.</p>
            </div>
            <div class="page-actions">
                <button class="btn-secondary">
                    <i class="bi bi-download"></i> Export Report
                </button>
                <a href="project-create.html" class="btn-primary">
                    <i class="bi bi-plus"></i> New Project
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon users">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-trend up">
                        <i class="bi bi-arrow-up"></i> 12.5%
                    </div>
                </div>
                <div class="stat-value">2,845</div>
                <div class="stat-label">Total Users</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon revenue">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="stat-trend up">
                        <i class="bi bi-arrow-up"></i> 8.2%
                    </div>
                </div>
                <div class="stat-value">$24,580</div>
                <div class="stat-label">Total Revenue</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon orders">
                        <i class="bi bi-cart"></i>
                    </div>
                    <div class="stat-trend down">
                        <i class="bi bi-arrow-down"></i> 3.1%
                    </div>
                </div>
                <div class="stat-value">1,249</div>
                <div class="stat-label">Total Orders</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon conversion">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="stat-trend up">
                        <i class="bi bi-arrow-up"></i> 5.7%
                    </div>
                </div>
                <div class="stat-value">4.8%</div>
                <div class="stat-label">Conversion Rate</div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Revenue Overview</h3>
                    <select class="form-select" style="width: auto; background: var(--card); color: var(--text); border-color: var(--border);">
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 90 days</option>
                    </select>
                </div>
                <div class="chart-container">
                    <div class="chart-placeholder py-2">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">User Acquisition</h3>
                    <select class="form-select" style="width: auto; background: var(--card); color: var(--text); border-color: var(--border);">
                        <option>Monthly</option>
                        <option>Quarterly</option>
                        <option>Yearly</option>
                    </select>
                </div>
                <div class="chart-container">
                    <div class="chart-placeholder py-2">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="table-card">
            <div class="table-header">
                <h3 class="table-title">Recent Orders</h3>
                <a href="#" class="btn-secondary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#ORD-7841</td>
                            <td>John Doe</td>
                            <td>Nov 15, 2023</td>
                            <td>$245.99</td>
                            <td><span class="status-badge status-completed">Completed</span></td>
                            <td><a href="#" class="text-muted">View</a></td>
                        </tr>
                        <tr>
                            <td>#ORD-7840</td>
                            <td>Sarah Smith</td>
                            <td>Nov 14, 2023</td>
                            <td>$1,299.00</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td><a href="#" class="text-muted">View</a></td>
                        </tr>
                        <tr>
                            <td>#ORD-7839</td>
                            <td>Michael Brown</td>
                            <td>Nov 13, 2023</td>
                            <td>$89.50</td>
                            <td><span class="status-badge status-active">Processing</span></td>
                            <td><a href="#" class="text-muted">View</a></td>
                        </tr>
                        <tr>
                            <td>#ORD-7838</td>
                            <td>Emily Johnson</td>
                            <td>Nov 12, 2023</td>
                            <td>$549.99</td>
                            <td><span class="status-badge status-completed">Completed</span></td>
                            <td><a href="#" class="text-muted">View</a></td>
                        </tr>
                        <tr>
                            <td>#ORD-7837</td>
                            <td>Robert Wilson</td>
                            <td>Nov 11, 2023</td>
                            <td>$299.00</td>
                            <td><span class="status-badge status-cancelled">Cancelled</span></td>
                            <td><a href="#" class="text-muted">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Task List & Activity Feed -->
        <div class="row">
            <div class="col-lg-6">
                <div class="tasks-card">
                    <div class="table-header mb-4">
                        <h3 class="table-title">My Tasks</h3>
                        <a href="#" class="btn-secondary">Add Task</a>
                    </div>
                    <ul class="task-list">
                        <li class="task-item">
                            <div class="task-checkbox completed">
                                <i class="bi bi-check"></i>
                            </div>
                            <div class="task-content">
                                <div class="task-title">Design dashboard layout</div>
                                <div class="task-meta">
                                    <span>Due: Today</span>
                                    <span class="task-priority priority-high">High</span>
                                </div>
                            </div>
                        </li>
                        <li class="task-item">
                            <div class="task-checkbox">
                                <i class="bi bi-check"></i>
                            </div>
                            <div class="task-content">
                                <div class="task-title">Prepare project report</div>
                                <div class="task-meta">
                                    <span>Due: Nov 20</span>
                                    <span class="task-priority priority-medium">Medium</span>
                                </div>
                            </div>
                        </li>
                        <li class="task-item">
                            <div class="task-checkbox">
                                <i class="bi bi-check"></i>
                            </div>
                            <div class="task-content">
                                <div class="task-title">Team meeting preparation</div>
                                <div class="task-meta">
                                    <span>Due: Nov 18</span>
                                    <span class="task-priority priority-low">Low</span>
                                </div>
                            </div>
                        </li>
                        <li class="task-item">
                            <div class="task-checkbox completed">
                                <i class="bi bi-check"></i>
                            </div>
                            <div class="task-content">
                                <div class="task-title">Update user documentation</div>
                                <div class="task-meta">
                                    <span>Due: Nov 15</span>
                                    <span class="task-priority priority-medium">Medium</span>
                                </div>
                            </div>
                        </li>
                        <li class="task-item">
                            <div class="task-checkbox">
                                <i class="bi bi-check"></i>
                            </div>
                            <div class="task-content">
                                <div class="task-title">Review client feedback</div>
                                <div class="task-meta">
                                    <span>Due: Nov 22</span>
                                    <span class="task-priority priority-high">High</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="activity-card">
                    <div class="table-header mb-4">
                        <h3 class="table-title">Recent Activity</h3>
                        <a href="#" class="btn-secondary">View All</a>
                    </div>
                    <div class="activity-timeline">
                            <div class="activity-item">
                                <div class="activity-dot"></div>
                                <div class="activity-content">
                                    <div class="activity-meta">
                                        <span class="activity-user">John Doe</span>
                                        <span>Today, 10:30 AM</span>
                                    </div>
                                    <div class="activity-text">Updated task status to "In Progress"</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-dot"></div>
                                <div class="activity-content">
                                    <div class="activity-meta">
                                        <span class="activity-user">Sarah Smith</span>
                                        <span>Yesterday, 3:45 PM</span>
                                    </div>
                                    <div class="activity-text">Added wireframes attachment</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-dot"></div>
                                <div class="activity-content">
                                    <div class="activity-meta">
                                        <span class="activity-user">Lisa Rodriguez</span>
                                        <span>Dec 11, 2025</span>
                                    </div>
                                    <div class="activity-text">Assigned task to John Doe and Sarah Smith</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-dot"></div>
                                <div class="activity-content">
                                    <div class="activity-meta">
                                        <span class="activity-user">Lisa Rodriguez</span>
                                        <span>Dec 10, 2025</span>
                                    </div>
                                    <div class="activity-text">Created task</div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </main>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\xampp\htdocs\smartboatTourProject\resources\views/admin/index.blade.php ENDPATH**/ ?>