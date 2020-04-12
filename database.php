
<?php

//This will make the database connection
function create_connection() {
    $DB_DSN = "mysql:host=localhost;dbname=db2";

    $DB_USER = "root";
    $DB_PASSWORD = "";
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    return $conn;
}

/**
 * this will be Inserting in parent table
 * @param type $conn
 */
function insert_parents($conn) {
    $success = FALSE;
    $query = "INSERT INTO parents(parent_id) VALUES(?)";
    $stmt = $conn->prepare($query);

    $stmt->bindValue(1, $conn->lastInsertId());
    if ($stmt->execute()) {
        $stmt = null;
        $success = TRUE;
    }
    return $success;
}

/**
 *  Register as parent
 * @param type $email
 * @param type $password
 * @param type $name
 * @param type $role
 * @param type $phone
 */
function register_parent($email, $password, $name, $role, $phone) {
    $conn = create_connection();
    $success = FALSE;
    if ($conn != NULL) {
        $inserted = insert_user($conn, $email, $password, $name, $phone);
        if ($inserted) {
            $success = insert_parents($conn);
        }
        $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, TRUE);
    }
    return $success;
}

/**
 *
 * @param type $query
 * @param type $conn
 * @param type $id
 * @return boolean
 */
function execute($query, $conn, $id) {
    $success = FALSE;
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $id);
    if ($stmt->execute()) {
        $stmt = NULL;
        $success = TRUE;
    }
    return $success;
}

/**
 * this will be inserting user data
 * @param type $conn
 * @param type $email
 * @param type $password
 * @param type $name
 * @param type $phone
 */
function insert_user($conn, $email, $password, $name, $phone) {
    $success = FALSE;
    try {
        $query = "INSERT INTO users(email,password,name,phone) VALUES(?,?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $email);
        $stmt->bindValue(2, $password);
        $stmt->bindValue(3, $name);
        $stmt->bindValue(4, $phone);
        if ($stmt->execute()) {
            $stmt = NULL;
            $success = TRUE;
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    return $success;
}

/**
 * this will be inserting into student table
 * @param type $conn
 */
function insert_student($conn, $parent_id, $grade, $student_id) {
    $success = FALSE;
    $query = "INSERT INTO students(student_id,grade,parent_id) VALUES(?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $student_id);
    $stmt->bindValue(2, $grade);
    $stmt->bindValue(3, $parent_id);
    if ($stmt->execute()) {
        $stmt = null;
        $success = TRUE;
    }
    return $success;
}

/**
 *
 * @param type $email
 * @param type $password
 * @param type $name
 * @param type $role
 * @param type $phone
 * @param type $grade
 */
function register_student($email, $parent, $password, $name, $role, $phone, $grade) {
    $conn = create_connection();
    $success = FALSE;
    if ($conn != NULL) {
        try {
            $inserted = insert_user($conn, $email, $password, $name, $phone);
            if ($inserted) {
                $student_id = $conn->lastInsertId();
                $parent_id = $parent['id'];
                $inserted = insert_student($conn, $parent_id, $grade, $student_id);
                if (2 == $role) {
                    $query = "INSERT INTO mentees(mentee_id) VALUES(?)";
                    if (execute($query, $conn, $student_id)) {
                        $success = TRUE;
                    }
                } else if (3 == $role) {
                    $query = "INSERT INTO mentors(mentor_id) VALUES(?)";
                    if (execute($query, $conn, $student_id)) {
                        $success = TRUE;
                    }
                } else if (4 == $role) {
                    $query = "INSERT INTO mentees(mentee_id) VALUES(?)";
                    $query1 = "INSERT INTO mentors(mentor_id) VALUES(?)";
                    if (execute($query, $conn, $student_id) && execute($query1, $conn, $student_id)) {
                        $success = TRUE;
                    }
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    return $success;
}

/**
 *
 * @param type $password
 * @param type $name
 * @param type $phone
 *
 */
function update_user($password, $name, $phone, $id) {
    $conn = create_connection();
    $success = FALSE;
    if ($conn != NULL) {
        $query = "UPDATE users set password=?,name=?,phone=? where id=?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $password);
        $stmt->bindValue(2, $name);
        $stmt->bindValue(3, $phone);
        $stmt->bindValue(4, $id);
        if ($stmt->execute()) {
            $stmt = null;
            $success = TRUE;
        }
    }
    return $success;
}

/**
 *
 * @param type $id
 * @param type $conn
 * @return string
 */
function getGrade($id, $conn) {
    $query = "SELECT grade from students where student_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $id);
    $grade = "";
    if ($stmt->execute()) {
        $row = $stmt->fetch();
        if ($row) {
            $grade = $row['grade'];
            if (6 == $grade)
                $grade = "Sixth";
            if (7 == $grade)
                $grade = "Seventh";
            if (8 == $grade)
                $grade = "Eighth";
            if (9 == $grade)
                $grade = "Nineth";
            if (10 == $grade)
                $grade = "Tenth";
            if (11 == $grade)
                $grade = "Eleventh";
            if (12 == $grade)
                $grade = "Twelth";
            $stmt = null;
        }
    }
    return $grade;
}

/**
 * this will get the user according to their email and password
 * @param type $email
 * @param type $password
 */
function get_user_by_email_and_password($email, $password) {
    $conn = create_connection();
    $user = NULL;
    if ($conn != NULL) {
        $query = "SELECT * from users where email=? and password=?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $email);
        $stmt->bindValue(2, $password);
        if ($stmt->execute()) {
            $row = $stmt->fetch();
            if ($row) {
                $user = populateUser($row, $conn);
            }
        }
        return $user;
    }
}

/**
 * this will get the user according to their email and password
 * @param type $email
 */
function get_user_by_email($email) {
    $conn = create_connection();
    $user = NULL;
    if ($conn != NULL) {
        $query = "SELECT * from users where email=?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $email);
        if ($stmt->execute()) {
            $row = $stmt->fetch();
            if ($row) {
                $user = populateUser($row, $conn);
            }
        }
        return $user;
    }
}

/**
 *
 * @param type $row
 */
function populateUser($row, $conn) {
    $user = array();
    $user['id'] = $row['id'];
    $user['name'] = $row['name'];
    $user['email'] = $row['email'];
    $user['phone'] = $row['phone'];
    $isAdmin = isAdmin($user['id'], $conn);
    if (!$isAdmin) {
        $user['isAdmin'] = FALSE;
        $isParent = isParent($user['id'], $conn);
        $user['isParent'] = $isParent;
        $isStudent = isStudent($user['id'], $conn);
        $user['isStudent'] = $isStudent;
        if ($isStudent) {
            $user['grade'] = getGrade($user['id'], $conn);
        }
        $isMentor = isMentor($user['id'], $conn);
        $user['isMentor'] = $isMentor;
        $isMentee = isMentee($user['id'], $conn);
        $user['isMentee'] = $isMentee;
    } else {
        $user['isAdmin'] = TRUE;
        $user['isStudent'] = FALSE;
        $user['isParent'] = FALSE;
    }
    return $user;
}

/**
 * this will check if the id given is for admin or not
 * @param type $id
 * @param type $conn
 * @return boolean
 */
function isAdmin($id, $conn) {
    $admin = FALSE;
    if ($conn != NULL) {
        $query = "SELECT * from admins where admin_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            $stmt = null;
            $admin = TRUE;
        }
    }
    return $admin;
}

/**
 * this will check if the id given is for parent or not
 * @param type $id
 * @param type $conn
 * @return boolean
 */
function isParent($id, $conn) {
    $parent = FALSE;
    if ($conn != NULL) {
        $query = "SELECT * from parents where parent_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            $stmt = null;
            $parent = TRUE;
        }
    }
    return $parent;
}

/**
 * this will check if the id given is for mentee or not
 * @param type $id
 * @param type $conn
 * @return boolean
 */
function isMentee($id, $conn) {
    $mentee = FALSE;
    if ($conn != NULL) {
        $query = "SELECT * from mentees where mentee_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            $stmt = null;
            $mentee = TRUE;
        }
    }
    return $mentee;
}

/**
 * this will check if the id given is for mentor or not
 * @param type $id
 * @param type $conn
 * @return boolean
 */
function isMentor($id, $conn) {
    $mentor = FALSE;
    if ($conn != NULL) {
        $query = "SELECT * from mentors where mentor_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            $stmt = null;
            $mentor = TRUE;
        }
    }
    return $mentor;
}

function isStudent($id, $conn) {
    $student = FALSE;
    if ($conn != NULL) {
        $query = "SELECT * from students where student_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            $stmt = null;
            $student = TRUE;
        }
    }
    return $student;
}

/**
 * this will get the user by id
 * @param type id
 */
function get_user_by_id($id) {
    $conn = create_connection();
    $user = NULL;
    if ($conn != NULL) {
        $query = "SELECT * from users where id=?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $id);
        if ($stmt->execute()) {
            $row = $stmt->fetch();
            $user = array();
            $user['id'] = $row['id'];
            $user['name'] = $row['name'];
            $user['email'] = $row['email'];
            $user['phone'] = $row['phone'];
        }
    }
    return $user;
}

/**
 * this will get children by their parent id
 * @param type $parentId
 */
function get_children_by_parent_id($parentId) {
    $conn = create_connection();
    $users = array();
    if ($conn != NULL) {
        $query = "SELECT u.id as id,u.name  as name,u.email  as email,u.phone as phone,s.grade as grade from users as u,students as s where s.parent_id=? and u.id=s.student_id";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $parentId);
        $stmt->execute();
        foreach ($stmt as $row):
            $user = array();
            $user['id'] = $row['id'];
            $user['name'] = $row['name'];
            $user['email'] = $row['email'];
            $user['phone'] = $row['phone'];
            $grade = getGrade($user['id'], $conn);
            $user['grade'] = $grade;
            array_push($users, $user);
        endforeach;
    }
    return $users;
}

function get_enrolled_mentor_count($conn, $meet_id) {
    $query = "select count(*) from enroll2 where meet_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $meet_id);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function get_enrolled_mentee_count($conn, $meet_id) {
    $query = "select count(*) from enroll where meet_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $meet_id);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function get_enroll_as_mentor($conn, $mentor_id, $meet_id) {
    $query = "select count(*) from enroll2 where mentor_id =? and meet_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $mentor_id);
    $stmt->bindValue(2, $meet_id);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count == 0)
        return "";

    return "N/A";
}

function get_enroll_as_mentee($conn, $mentee_id, $meet_id) {
    $query = "select count(*) from enroll where mentee_id =? and meet_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $mentee_id);
    $stmt->bindValue(2, $meet_id);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count == 0)
        return "";

    return "N/A";
}

function get_grade($id, $conn) {
    $query = "SELECT grade from students where student_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $id);
    $grade = "";
    if ($stmt->execute()) {
        $row = $stmt->fetch();
        if ($row) {
            $grade = $row['grade'];
            $stmt = null;
        }
    }
    return $grade;
}

/**
 * this will get session data
 */
function get_session_data($userId) {
    $conn = create_connection();
    $sections = NULL;
    if ($conn != NULL) {
        $sections = array();
        $grade = get_grade($userId, $conn);
        $isMentor = isMentor($userId, $conn);
        $isMentee = isMentee($userId, $conn);
        $query = "select m.meet_id, g.name, m.meet_name, m.date, t.day_of_the_week, t.start_time, t.end_time, m.capacity, g.mentee_grade_req, g.mentor_grade_req from groups as g, meetings as m, time_slot as t where m.time_slot_id = t.time_slot_id and m.group_id = g.group_id";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        foreach ($stmt as $row):
            $section = array();
            $section['meet_id'] = $row['meet_id'];
            $section['name'] = $row['name'];
            $section['meet_name'] = $row['meet_name'];
            $section['date'] = $row['date'];
            $section['start_time'] = $row['start_time'];
            $section['end_time'] = $row['end_time'];
            $section['time_slot'] = $row['day_of_the_week'] . " " . $row['start_time'] . " - " . $row['end_time'];
            $section['capacity'] = $row['capacity'];
            $section['mentee_grade_req'] = $row['mentee_grade_req'] == NULL ? 0 : $row['mentee_grade_req'];
            $section['mentor_grade_req'] = $row['mentor_grade_req'] == NULL ? 0 : $row['mentor_grade_req'];
            $section['enrolled_mentor'] = get_enrolled_mentor_count($conn, $row['meet_id']);
            $section['enrolled_mentee'] = get_enrolled_mentee_count($conn, $row['meet_id']);
            $section['enroll_as_Mentor'] = get_enroll_as_mentor($conn, $userId, $row['meet_id']);
            $section['enroll_as_Mentee'] = get_enroll_as_mentee($conn, $userId, $row['meet_id']);
            $isMentor = isMentor($userId, $conn);
            $section['isMentor'] = $isMentor;
            $isMentee = isMentee($userId, $conn);
            $section['isMentee'] = $isMentee;
            array_push($sections, $section);
        endforeach;
    }
    return $sections;
}

/**
 * this will get all the groups
 * @return arrayGet
 */
function get_all_groups() {
    $conn = create_connection();
    $groups = NULL;
    if (NULL != $conn) {
        try {
            $groups = array();
            $query = "Select * from groups";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            foreach ($stmt as $row):
                $group = array();
                $group['group_id'] = $row['group_id'];
                $group['name'] = $row['name'];
                $group['description'] = $row['description'];
                $group['mentee_grade_req'] = $row['mentee_grade_req'];
                $group['mentor_grade_req'] = $row['mentor_grade_req'];
                array_push($groups, $group);
            endforeach;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    return $groups;
}

/**
 * this will add the group
 * @param type $groupName
 * @param type $desc
 * @param type $req_mentee
 * @param type $req_mentor
 */
function add_group($groupName, $desc, $req_mentee, $req_mentor) {
    $conn = create_connection();
    $success = FALSE;
    if (NULL != $conn) {
        try {
            $query = "INSERT INTO groups(name, description, mentee_grade_req, mentor_grade_req) VALUES(?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $groupName);
            $stmt->bindValue(2, $desc);
            $stmt->bindValue(3, $req_mentee);
            $stmt->bindValue(4, $req_mentor);
            if ($stmt->execute()) {
                $stmt = null;
                $success = TRUE;
            }
        } catch (Exception $exc) {
            echo $e->getMessage();
        }
    }
    return $success;
}

/**
 *
 * @param type $day_of_the_week
 * @param type $start_time
 * @param type $end_time
 * @return boolean
 */
function add_timeslot($day_of_the_week, $start_time, $end_time) {
    $conn = create_connection();
    $success = FALSE;
    if (NULL != $conn) {
        try {
            $query = "INSERT INTO time_slot(day_of_the_week, start_time, end_time) VALUES(?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $day_of_the_week);
            $stmt->bindValue(2, $start_time);
            $stmt->bindValue(3, $end_time);
            if ($stmt->execute()) {
                $stmt = null;
                $success = TRUE;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    return $success;
}

function get_all_timeslots() {
    $conn = create_connection();
    $timeslots = NULL;
    if (NULL != $conn) {
        try {
            $timeslots = array();
            $query = "Select * from time_slot";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            foreach ($stmt as $row):
                $timeslot = array();
                $timeslot['time_slot_id'] = $row['time_slot_id'];
                $timeslot['day_of_week'] = $row['day_of_the_week'];
                $timeslot['day_of_week_full'] = get_full_day($timeslot['day_of_week']);
                $timeslot['start_time'] = $row['start_time'];
                $timeslot['end_time'] = $row['end_time'];
                array_push($timeslots, $timeslot);
            endforeach;
        } catch (Exception $exc) {
            echo $e->getMessage();
        }
    }
    return $timeslots;
}

function get_full_day($day_of_week_short) {
    if ("S" == $day_of_week_short) {
        return "Sunday";
    } else if ("M" == $day_of_week_short) {
        return "Monday";
    } else if ("T" == $day_of_week_short) {
        return "Tuesday";
    } else if ("W" == $day_of_week_short) {
        return "Wednesday";
    } else if ("Th" == $day_of_week_short) {
        return "Thursday";
    } else if ("F" == $day_of_week_short) {
        return "Friday";
    } else if ("Sa" == $day_of_week_short) {
        return "Saturday";
    }
    return "";
}

/**
 * this will  add meeting
 * @param type $name
 * @param type $announcement
 * @param type $start_date
 * @param type $capacity
 * @param type $group
 * @param type $time_slot
 * @return boolean
 */
function add_meetings($name, $announcement, $start_date, $capacity, $group, $time_slot) {
    $conn = create_connection();
    $success = FALSE;
    if (NULL != $conn) {
        try {
            $query = "INSERT INTO meetings(meet_name, announcement, date, capacity, time_slot_id, group_id) VALUES(?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $name);
            $stmt->bindValue(2, $announcement);
            $stmt->bindValue(3, $start_date);
            $stmt->bindValue(4, $capacity);
            $stmt->bindValue(5, $time_slot);
            $stmt->bindValue(6, $group);
            if ($stmt->execute()) {
                $stmt = null;
                $success = TRUE;
            }
        } catch (Exception $exc) {
            echo $e->getMessage();
        }
    }
    return $success;
}

function get_all_meetings() {
    $conn = create_connection();
    $sections = NULL;
    if ($conn != NULL) {
        $sections = array();
        $query = "select m.meet_id, g.name, m.meet_name, m.date, t.day_of_the_week, t.start_time, t.end_time, m.capacity, g.mentee_grade_req, g.mentor_grade_req from groups as g, meetings as m, time_slot as t where m.time_slot_id = t.time_slot_id and m.group_id = g.group_id";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        foreach ($stmt as $row):
            $section = array();
            $section['name'] = $row['name'];
            $section['meet_id'] = $row['meet_id'];
            $section['meet_name'] = $row['meet_name'];
            $section['date'] = $row['date'];
            $section['start_time'] = $row['start_time'];
            $section['end_time'] = $row['end_time'];
            $section['time_slot'] = $row['day_of_the_week'] . " " . $row['start_time'] . " - " . $row['end_time'];
            $section['capacity'] = $row['capacity'];
            $section['mentee_grade_req'] = $row['mentee_grade_req'] == NULL ? 0 : $row['mentee_grade_req'];
            $section['mentor_grade_req'] = $row['mentor_grade_req'] == NULL ? 0 : $row['mentor_grade_req'];
            $section['enrolled_mentor'] = get_enrolled_mentor_count($conn, $row['meet_id']);
            $section['enrolled_mentee'] = get_enrolled_mentee_count($conn, $row['meet_id']);
            array_push($sections, $section);
        endforeach;
    }
    return $sections;
}

function assign_material($conn, $material_id, $meet_id) {
    $query = "INSERT INTO assign(material_id, meet_id) VALUES(?, ?)";
    $success = FALSE;
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $material_id);
    $stmt->bindValue(2, $meet_id);
    if ($stmt->execute()) {
        $stmt = null;
        $success = TRUE;
    }
    return $success;
}

/**
 * this will add material
 * @param type $name
 * @param type $author
 * @param type $type
 * @param type $url
 * @param type $notes
 * @param type $meet_id
 * @return boolean
 */
function add_material($name, $author, $type, $url, $notes, $meet_id) {
    $conn = create_connection();
    $success = FALSE;
    if (NULL != $conn) {
        try {
            $query = "INSERT INTO material(title, author, type, url, notes, assigned_date) VALUES(?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $name);
            $stmt->bindValue(2, $author);
            $stmt->bindValue(3, $type);
            $stmt->bindValue(4, $url);
            $stmt->bindValue(5, $notes);
            $date = date("y-m-d");
            $stmt->bindValue(6, $date);
            if ($stmt->execute()) {
                $material_id = $conn->lastInsertId();
                if (assign_material($conn, $material_id, $meet_id)) {
                    $stmt = null;
                    $success = TRUE;
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    return $success;
}


function get_all_material($meet_id) {
    $materials = NULL;
    $conn = create_connection();
    if ($conn != NULL) {
        $materials = array();
        $query = "select meet_name, m.date, m.announcement, mt.title, mt.author, mt.type, mt.url, mt.notes, mt.assigned_date from meetings as m, material as mt, assign as a where mt.material_id = a.material_id and a.meet_id = m.meet_id and a.meet_id = " . $meet_id;
        $stmt = $conn->prepare($query);
        $stmt->execute();
        foreach ($stmt as $row):
            $material = array();
            $material['meet_name'] = $row['meet_name'];
            $material['date'] = $row['date'];
            $material['announcement'] = $row['announcement'];
            $material['title'] = $row['title'];
            $material['author'] = $row['author'];
            $material['type'] = $row['type'];
            $material['url'] = $row['url'];
            $material['notes'] = $row['notes'];
            $material['assigned_date'] = $row['assigned_date'];
            array_push($materials, $material);
        endforeach;
    }
    return $materials;
}

/**
 * this will get mentee grade
 * @param type $meet_id
 * @param type $conn
 */
function get_mentee_grade($meet_id, $conn) {
    $query = "SELECT g.mentee_grade_req from groups as g where g.group_id = (select group_id from meetings where meet_id = ?)";
    $grade = "";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $meet_id);
    if ($stmt->execute()) {
        $row = $stmt->fetch();
        if ($row) {
            $grade = $row ['mentee_grade_req'];
        }
    }
    return $grade;
}

function get_mentor_grade($meet_id, $conn) {
    $query = "SELECT g.mentor_grade_req from groups as g where g.group_id = (select group_id from meetings where meet_id = ?)";
    $grade = "";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $meet_id);
    if ($stmt->execute()) {
        $row = $stmt->fetch();
        if ($row) {
            $grade = $row ['mentor_grade_req'];
        }
    }
    return $grade;
}

/**
 *
 * @param type $meet_id
 */
function get_meeting_mentees($meet_id) {
    $conn = create_connection();
    $mentees = NULL;
    if ($conn != NULL) {
        $mentees = array();
        $max_grade = get_mentee_grade($meet_id, $conn);
        $query = "SELECT u.id, u.name from users as u, students as s, mentees as m,enroll as e where e.mentee_id=u.id and u.id = s.student_id and u.id = m.mentee_id and s.grade <= ?";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(1, $max_grade);
        $stmt->execute();
        foreach ($stmt as $row):
            $user = array();
            $user['id'] = $row['id'];
            $user['name'] = $row['name'];
            array_push($mentees, $user);
        endforeach;
    }

    return $mentees;
}

/**
 *
 * @param type $meet_id
 */
function get_meeting_mentors($meet_id) {
    $conn = create_connection();
    $mentors = NULL;
    if ($conn != NULL) {
        $mentors = array();
        $max_grade = get_mentor_grade($meet_id, $conn);
        $query = "SELECT u.id, u.name from users as u, students as s, mentors as m, enroll2 as e where e.mentor_id=u.id and u.id = s.student_id and u.id = m.mentor_id and s.grade >= ?";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(1, $max_grade);
        $stmt->execute();
        foreach ($stmt as $row):
            $user = array();
            $user['id'] = $row['id'];
            $user['name'] = $row['name'];
            array_push($mentors, $user);
        endforeach;
    }
    return $mentors;
}

function enroll_mentee($meet_id, $mentee_id) {
    $success = FALSE;
    $conn = create_connection();
    if (NULL != $conn) {
        try {
            $query = "INSERT INTO enroll(meet_id, mentee_id) VALUES(?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $meet_id);
            $stmt->bindValue(2, $mentee_id);
            if ($stmt->execute()) {
                $stmt = null;
                $success = TRUE;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    return $success;
}

/**
 * this will  enroll as mentor
 * @param type $meet_id
 * @param type $mentor_id
 * @return boolean
 */
function enroll_mentor($meet_id, $mentor_id) {
    $conn = create_connection();
    $success = FALSE;
    if (NULL != $conn) {
        try {
            $query = "INSERT INTO enroll2(meet_id, mentor_id) VALUES(?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $meet_id);
            $stmt->bindValue(2, $mentor_id);
            if ($stmt->execute()) {
                $stmt = null;
                $success = TRUE;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    return $success;
}

/**
 *
 * @param type $meet_id
 */
function cancel_meeting($meet_id) {
    $conn = create_connection();
    $success = FALSE;
    if (NULL != $conn) {
        try {
            $query = "delete from meetings where meet_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $meet_id);
            $stmt->execute();
            $stmt = null;
            $success = TRUE;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    return $success;
}

/**
 * this will get the mentee
 */
function get_mentees($user_id) {
    $meeting_memtee = [];
    $meetings = get_mentee_meetings($user_id);
    if (null != $meetings) {
        foreach ($meetings as $meet_id) {
            $meet_name = get_meeting($meet_id);
            if (null != $meet_name) {
                $meeting_memtee[$meet_name] = get_meeting_mentees($meet_id);
            }
        }
    }
    return $meeting_memtee;
}

/**
 * this will get the mentors
 */
function get_mentors($user_id) {
    $meeting_memtors = [];
    $meetings = get_mentee_meetings($user_id);
    if (null != $meetings) {
        foreach ($meetings as $meet_id) {
            $meet_name = get_meeting($meet_id);
            if (null != $meet_name) {
                $meeting_memtors[$meet_name] = get_meeting_mentors($meet_id);
            }
        }
    }
    return $meeting_memtors;
}

function get_mentee_meetings($user_id) {
    $conn = create_connection();
    $meetings = NULL;
    if ($conn != NULL) {
        $meetings = array();
        $query = "SELECT meet_id from enroll where mentee_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        foreach ($stmt as $row):
            array_push($meetings, $row['meet_id']);
        endforeach;
    }
    return $meetings;
}

function get_mentor_meetings($user_id) {
    $conn = create_connection();
    $meetings = NULL;
    if ($conn != NULL) {
        $meetings = array();
        $query = "SELECT meet_id from enroll2 where mentor_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        foreach ($stmt as $row):
            array_push($meetings, $row['meet_id']);
        endforeach;
    }
    return $meetings;
}

function get_meeting($meet_id) {
    $conn = create_connection();
    $meet_name = NULL;
    if ($conn != NULL) {
        $query = "SELECT meet_name from meetings where meet_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(1, $meet_id);
        if ($stmt->execute()) {
            $row = $stmt->fetch();
            if ($row) {
                $meet_name = $row['meet_name'];
            }
        }
    }
    return $meet_name;
}

function get_enroll_as_mentor_count($mentor_id, $meet_id) {
    $query = "select count(*) from enroll2 where mentor_id =? and meet_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $mentor_id);
    $stmt->bindValue(2, $meet_id);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count;
}

function get_enroll_as_mentee_count($mentee_id, $meet_id) {
    $query = "select count(*) from enroll where mentee_id =? and meet_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $mentee_id);
    $stmt->bindValue(2, $meet_id);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count;
}

function quit_student_meeting($meet_id, $userId, $query) {
    $conn = create_connection();
    $success = FALSE;
    if (NULL != $conn) {
        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $meet_id);
            $stmt->bindValue(2, $userId);
            $stmt->execute();
            $stmt = null;
            $success = TRUE;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    return $success;
}

function quit_meeting($meet_id, $user_id, $isMentor, $isMentee) {
    $success = FALSE;
    if ($isMentee) {
        $query = " delete from enroll where meet_id=? and mentee_id=?";
        $success = quit_student_meeting($meet_id, $user_id, $query);
    }
    if ($isMentor) {
        $query = " delete from enrol2 where meet_id=? and mentor_id=?";
        $success = quit_student_meeting($meet_id, $user_id, $query);
    }
    return $success;
}
