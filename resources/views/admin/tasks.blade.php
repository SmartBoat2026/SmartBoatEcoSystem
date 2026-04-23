@extends('admin.layouts.app');
@section('content');

    <!-- ========== MAIN CONTENT ========== -->
   <main class="main" id="main" role="main">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h1>Tasks</h1>
                <p>Manage and track all your tasks in one place</p>
            </div>
            <div class="page-actions">
                <button class="btn-secondary">
                    <i class="bi bi-filter"></i> Filter
                </button>
                <a href="task-create.html" class="btn-primary">
                    <i class="bi bi-plus"></i> New Task
                </a>
            </div>
        </div>

        <!-- Tasks Header -->
        <div class="task-page tasks-header">
            <div class="filter-controls">
                <div class="view-toggle">
                    <button class="view-btn active" id="listViewBtn">
                        <i class="bi bi-list"></i> List
                    </button>
                    <button class="view-btn" id="gridViewBtn">
                        <i class="bi bi-grid-3x3-gap"></i> Grid
                    </button>
                </div>
                <button class="filter-btn active" data-filter="all">All Tasks</button>
                <button class="filter-btn" data-filter="todo">To Do</button>
                <button class="filter-btn" data-filter="in-progress">In Progress</button>
                <button class="filter-btn" data-filter="review">Review</button>
                <button class="filter-btn" data-filter="completed">Completed</button>
                <button class="filter-btn" data-filter="high">High Priority</button>
            </div>
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Search tasks...">
            </div>
        </div>

        <!-- Empty State (Hidden by default) -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <i class="bi bi-check2-square"></i>
            <h3>No tasks found</h3>
            <p>Try adjusting your search or filter to find what you're looking for.</p>
        </div>

        <!-- Tasks List View -->
        <div class="tasks-list-view" id="listView">
            <div class="tasks-table-container">
                <div class="tasks-table-header">
                    <h3 class="section-title mb-0">All Tasks</h3>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted">Showing 12 of 45 tasks</span>
                        <select class="form-select" style="width: auto; background: var(--card); color: var(--text); border-color: var(--border);">
                            <option>Sort by: Due Date</option>
                            <option>Sort by: Priority</option>
                            <option>Sort by: Status</option>
                            <option>Sort by: Assignee</option>
                        </select>
                    </div>
                </div>
                <table class="tasks-table">
                    <thead>
                        <tr>
                            <th class="task-checkbox-cell"></th>
                            <th>Task</th>
                            <th>Assignee</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tasksTableBody">
                        <!-- Task 1 -->
                        <tr class="task-row" data-status="in-progress" data-priority="high">
                            <td class="task-checkbox-cell">
                                <div class="task-checkbox">
                                    <i class="bi bi-check"></i>
                                </div>
                            </td>
                            <td class="task-title-cell">
                                <div class="task-title">Design dashboard layout components</div>
                                <div class="task-project">
                                    <i class="bi bi-kanban"></i>
                                    <a href="project-details.html">Dashboard Redesign</a>
                                </div>
                            </td>
                            <td>
                                <div class="task-assignee">
                                    <div class="assignee-avatar" style="background: #00B7FF; color: white;">SS</div>
                                    <span>Sarah Smith</span>
                                </div>
                            </td>
                            <td><span class="task-priority priority-high">High</span></td>
                            <td><span class="task-status status-in-progress">In Progress</span></td>
                            <td class="task-due-date">
                                <span class="due-date">Dec 5, 2023</span>
                                <span class="days-left">2 days left</span>
                            </td>
                            <td>
                               <div class="task-actions">
                                    <a href="task-create.html" class="task-action-btn" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="task-details.html" class="task-action-btn" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="task-action-btn" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Task 2 -->
                        <tr class="task-row" data-status="completed" data-priority="medium">
                            <td class="task-checkbox-cell">
                                <div class="task-checkbox completed">
                                    <i class="bi bi-check"></i>
                                </div>
                            </td>
                            <td class="task-title-cell">
                                <div class="task-title">Implement dark mode toggle</div>
                                <div class="task-project">
                                    <i class="bi bi-kanban"></i>
                                    <a href="project-details.html">Dashboard Redesign</a>
                                </div>
                            </td>
                            <td>
                                <div class="task-assignee">
                                    <div class="assignee-avatar" style="background: #10b981; color: white;">MB</div>
                                    <span>Michael Brown</span>
                                </div>
                            </td>
                            <td><span class="task-priority priority-medium">Medium</span></td>
                            <td><span class="task-status status-completed">Completed</span></td>
                            <td class="task-due-date">
                                <span class="due-date">Nov 25, 2023</span>
                                <span class="days-left">Completed</span>
                            </td>
                            <td>
                                <div class="task-actions">
                                    <a href="task-create.html" class="task-action-btn" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="task-details.html" class="task-action-btn" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="task-action-btn" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Task 3 -->
                        <tr class="task-row" data-status="in-progress" data-priority="high">
                            <td class="task-checkbox-cell">
                                <div class="task-checkbox">
                                    <i class="bi bi-check"></i>
                                </div>
                            </td>
                            <td class="task-title-cell">
                                <div class="task-title">Integrate charting libraries for analytics</div>
                                <div class="task-project">
                                    <i class="bi bi-kanban"></i>
                                    <a href="project-details.html">Dashboard Redesign</a>
                                </div>
                            </td>
                            <td>
                                <div class="task-assignee">
                                    <div class="assignee-avatar" style="background: #f59e0b; color: white;">EJ</div>
                                    <span>Emily Johnson</span>
                                </div>
                            </td>
                            <td><span class="task-priority priority-high">High</span></td>
                            <td><span class="task-status status-in-progress">In Progress</span></td>
                            <td class="task-due-date">
                                <span class="due-date">Dec 8, 2023</span>
                                <span class="days-left">5 days left</span>
                            </td>
                            <td>
                                <div class="task-actions">
                                    <a href="task-create.html" class="task-action-btn" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="task-details.html" class="task-action-btn" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="task-action-btn" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Task 4 -->
                        <tr class="task-row" data-status="completed" data-priority="low">
                            <td class="task-checkbox-cell">
                                <div class="task-checkbox completed">
                                    <i class="bi bi-check"></i>
                                </div>
                            </td>
                            <td class="task-title-cell">
                                <div class="task-title">Create user documentation</div>
                                <div class="task-project">
                                    <i class="bi bi-kanban"></i>
                                    <a href="project-details.html">Dashboard Redesign</a>
                                </div>
                            </td>
                            <td>
                                <div class="task-assignee">
                                    <div class="assignee-avatar" style="background: #ef4444; color: white;">RW</div>
                                    <span>Robert Wilson</span>
                                </div>
                            </td>
                            <td><span class="task-priority priority-low">Low</span></td>
                            <td><span class="task-status status-completed">Completed</span></td>
                            <td class="task-due-date">
                                <span class="due-date">Nov 28, 2023</span>
                                <span class="days-left">Completed</span>
                            </td>
                            <td>
                                <div class="task-actions">
                                    <a href="task-create.html" class="task-action-btn" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="task-details.html" class="task-action-btn" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="task-action-btn" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Task 5 -->
                        <tr class="task-row" data-status="review" data-priority="medium">
                            <td class="task-checkbox-cell">
                                <div class="task-checkbox">
                                    <i class="bi bi-check"></i>
                                </div>
                            </td>
                            <td class="task-title-cell">
                                <div class="task-title">Responsive design testing on mobile devices</div>
                                <div class="task-project">
                                    <i class="bi bi-kanban"></i>
                                    <a href="project-details.html">Dashboard Redesign</a>
                                </div>
                            </td>
                            <td>
                                <div class="task-assignee">
                                    <div class="assignee-avatar" style="background: #00B7FF; color: white;">SS</div>
                                    <span>Sarah Smith</span>
                                </div>
                            </td>
                            <td><span class="task-priority priority-medium">Medium</span></td>
                            <td><span class="task-status status-review">Review</span></td>
                            <td class="task-due-date">
                                <span class="due-date">Dec 12, 2023</span>
                                <span class="days-left">9 days left</span>
                            </td>
                            <td>
                               <div class="task-actions">
                                    <a href="task-create.html" class="task-action-btn" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="task-details.html" class="task-action-btn" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="task-action-btn" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Task 6 -->
                        <tr class="task-row" data-status="todo" data-priority="high">
                            <td class="task-checkbox-cell">
                                <div class="task-checkbox">
                                    <i class="bi bi-check"></i>
                                </div>
                            </td>
                            <td class="task-title-cell">
                                <div class="task-title">Performance optimization and code review</div>
                                <div class="task-project">
                                    <i class="bi bi-kanban"></i>
                                    <a href="project-details.html">Dashboard Redesign</a>
                                </div>
                            </td>
                            <td>
                                <div class="task-assignee">
                                    <div class="assignee-avatar" style="background: #10b981; color: white;">MB</div>
                                    <span>Michael Brown</span>
                                </div>
                            </td>
                            <td><span class="task-priority priority-high">High</span></td>
                            <td><span class="task-status status-todo">To Do</span></td>
                            <td class="task-due-date">
                                <span class="due-date">Dec 18, 2023</span>
                                <span class="days-left">15 days left</span>
                            </td>
                            <td>
                                <div class="task-actions">
                                    <a href="task-create.html" class="task-action-btn" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="task-details.html" class="task-action-btn" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="task-action-btn" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tasks Grid View -->
        <div class="tasks-grid-view" id="gridView">
            <div class="tasks-grid" id="tasksGrid">
                <!-- Task Card 1 -->
                <div class="task-card" data-status="in-progress" data-priority="high">
                    <div class="task-card-header">
                        <div class="task-card-checkbox">
                            <i class="bi bi-check"></i>
                        </div>
                        <div class="task-card-title">
                            <h4>Design dashboard layout components</h4>
                            <div class="task-card-project">
                                <i class="bi bi-kanban"></i>
                                <a href="project-details.html">Dashboard Redesign</a>
                            </div>
                        </div>
                    </div>
                    <p class="task-card-description">
                        Create responsive layout components for the new Panelry Dashboard with modern design patterns.
                    </p>
                    <div class="task-card-meta">
                        <div class="meta-item">
                            <i class="bi bi-calendar"></i>
                            <span>Due: Dec 5, 2023</span>
                        </div>
                        <div class="meta-item">
                            <span class="task-priority priority-high">High</span>
                        </div>
                    </div>
                    <div class="task-card-footer">
                        <div class="task-card-assignee">
                            <div class="card-assignee-avatar" style="background: #00B7FF; color: white;">SS</div>
                            <div>
                                <div class="assignee-name">Sarah Smith</div>
                                <div class="task-status status-in-progress">In Progress</div>
                            </div>
                        </div>
                        <div class="task-card-actions">
                            <a href="task-create.html" class="card-action-btn" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="task-details.html" class="card-action-btn" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Task Card 2 -->
                <div class="task-card" data-status="completed" data-priority="medium">
                    <div class="task-card-header">
                        <div class="task-card-checkbox completed">
                            <i class="bi bi-check"></i>
                        </div>
                        <div class="task-card-title">
                            <h4>Implement dark mode toggle</h4>
                            <div class="task-card-project">
                                <i class="bi bi-kanban"></i>
                                <a href="project-details.html">Dashboard Redesign</a>
                            </div>
                        </div>
                    </div>
                    <p class="task-card-description">
                        Add theme switching functionality with system preference detection and user override.
                    </p>
                    <div class="task-card-meta">
                        <div class="meta-item">
                            <i class="bi bi-calendar"></i>
                            <span>Completed: Nov 25, 2023</span>
                        </div>
                        <div class="meta-item">
                            <span class="task-priority priority-medium">Medium</span>
                        </div>
                    </div>
                    <div class="task-card-footer">
                        <div class="task-card-assignee">
                            <div class="card-assignee-avatar" style="background: #10b981; color: white;">MB</div>
                            <div>
                                <div class="assignee-name">Michael Brown</div>
                                <div class="task-status status-completed">Completed</div>
                            </div>
                        </div>
                        <div class="task-card-actions">
                            <a href="task-create.html" class="card-action-btn" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="task-details.html" class="card-action-btn" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Task Card 3 -->
                <div class="task-card" data-status="in-progress" data-priority="high">
                    <div class="task-card-header">
                        <div class="task-card-checkbox">
                            <i class="bi bi-check"></i>
                        </div>
                        <div class="task-card-title">
                            <h4>Integrate charting libraries</h4>
                            <div class="task-card-project">
                                <i class="bi bi-kanban"></i>
                                <a href="project-details.html">Dashboard Redesign</a>
                            </div>
                        </div>
                    </div>
                    <p class="task-card-description">
                        Integrate Chart.js for data visualization and create interactive analytics components.
                    </p>
                    <div class="task-card-meta">
                        <div class="meta-item">
                            <i class="bi bi-calendar"></i>
                            <span>Due: Dec 8, 2023</span>
                        </div>
                        <div class="meta-item">
                            <span class="task-priority priority-high">High</span>
                        </div>
                    </div>
                    <div class="task-card-footer">
                        <div class="task-card-assignee">
                            <div class="card-assignee-avatar" style="background: #f59e0b; color: white;">EJ</div>
                            <div>
                                <div class="assignee-name">Emily Johnson</div>
                                <div class="task-status status-in-progress">In Progress</div>
                            </div>
                        </div>
                        <div class="task-card-actions">
                            <a href="task-create.html" class="card-action-btn" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="task-details.html" class="card-action-btn" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Task Card 4 -->
                <div class="task-card" data-status="completed" data-priority="low">
                    <div class="task-card-header">
                        <div class="task-card-checkbox completed">
                            <i class="bi bi-check"></i>
                        </div>
                        <div class="task-card-title">
                            <h4>Create user documentation</h4>
                            <div class="task-card-project">
                                <i class="bi bi-kanban"></i>
                                <a href="project-details.html">Dashboard Redesign</a>
                            </div>
                        </div>
                    </div>
                    <p class="task-card-description">
                        Write comprehensive user guide and technical documentation for the dashboard features.
                    </p>
                    <div class="task-card-meta">
                        <div class="meta-item">
                            <i class="bi bi-calendar"></i>
                            <span>Completed: Nov 28, 2023</span>
                        </div>
                        <div class="meta-item">
                            <span class="task-priority priority-low">Low</span>
                        </div>
                    </div>
                    <div class="task-card-footer">
                        <div class="task-card-assignee">
                            <div class="card-assignee-avatar" style="background: #ef4444; color: white;">RW</div>
                            <div>
                                <div class="assignee-name">Robert Wilson</div>
                                <div class="task-status status-completed">Completed</div>
                            </div>
                        </div>
                       <div class="task-card-actions">
                            <a href="task-create.html" class="card-action-btn" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="task-details.html" class="card-action-btn" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Task Card 5 -->
                <div class="task-card" data-status="review" data-priority="medium">
                    <div class="task-card-header">
                        <div class="task-card-checkbox">
                            <i class="bi bi-check"></i>
                        </div>
                        <div class="task-card-title">
                            <h4>Responsive design testing</h4>
                            <div class="task-card-project">
                                <i class="bi bi-kanban"></i>
                                <a href="project-details.html">Dashboard Redesign</a>
                            </div>
                        </div>
                    </div>
                    <p class="task-card-description">
                        Test dashboard responsiveness across different screen sizes and devices.
                    </p>
                    <div class="task-card-meta">
                        <div class="meta-item">
                            <i class="bi bi-calendar"></i>
                            <span>Due: Dec 12, 2023</span>
                        </div>
                        <div class="meta-item">
                            <span class="task-priority priority-medium">Medium</span>
                        </div>
                    </div>
                    <div class="task-card-footer">
                        <div class="task-card-assignee">
                            <div class="card-assignee-avatar" style="background: #00B7FF; color: white;">SS</div>
                            <div>
                                <div class="assignee-name">Sarah Smith</div>
                                <div class="task-status status-review">Review</div>
                            </div>
                        </div>
                       <div class="task-card-actions">
                            <a href="task-create.html" class="card-action-btn" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="task-details.html" class="card-action-btn" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Task Card 6 -->
                <div class="task-card" data-status="todo" data-priority="high">
                    <div class="task-card-header">
                        <div class="task-card-checkbox">
                            <i class="bi bi-check"></i>
                        </div>
                        <div class="task-card-title">
                            <h4>Performance optimization</h4>
                            <div class="task-card-project">
                                <i class="bi bi-kanban"></i>
                                <a href="project-details.html">Dashboard Redesign</a>
                            </div>
                        </div>
                    </div>
                    <p class="task-card-description">
                        Optimize loading times, implement code splitting, and reduce bundle size.
                    </p>
                    <div class="task-card-meta">
                        <div class="meta-item">
                            <i class="bi bi-calendar"></i>
                            <span>Due: Dec 18, 2023</span>
                        </div>
                        <div class="meta-item">
                            <span class="task-priority priority-high">High</span>
                        </div>
                    </div>
                    <div class="task-card-footer">
                        <div class="task-card-assignee">
                            <div class="card-assignee-avatar" style="background: #10b981; color: white;">MB</div>
                            <div>
                                <div class="assignee-name">Michael Brown</div>
                                <div class="task-status status-todo">To Do</div>
                            </div>
                        </div>
                       <div class="task-card-actions">
                            <a href="task-create.html" class="card-action-btn" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="task-details.html" class="card-action-btn" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <nav aria-label="Task pagination">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a>
                </li>
                <li class="page-item">
                    <a class="page-link active" href="#">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">3</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
    </main>

    @endsection