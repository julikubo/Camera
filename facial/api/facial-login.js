import { createClient } from '@supabase/supabase-js';
import { FACE_USER_MAPPING } from '../face-mapping.js';

const supabase = createClient(
    process.env.SUPABASE_URL,
    process.env.SUPABASE_ANON_KEY
);

export default async function handler(req, res) {
    // Enable CORS
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

    if (req.method === 'OPTIONS') {
        return res.status(200).end();
    }

    if (req.method !== 'POST') {
        return res.status(405).json({ success: false, message: 'Method not allowed' });
    }

    try {
        const { face_name } = req.body;

        if (!face_name) {
            return res.status(400).json({
                success: false,
                message: 'Face name is required'
            });
        }

        // Check if face is mapped
        const userEmail = FACE_USER_MAPPING[face_name];

        if (!userEmail) {
            return res.status(404).json({
                success: false,
                message: 'Face recognized but not mapped to any user',
                face_name
            });
        }

        // Query Supabase for user by email
        const { data: users, error } = await supabase
            .from('users')
            .select('*')
            .eq('email', userEmail)
            .limit(1);

        if (error) {
            console.error('Supabase error:', error);
            return res.status(500).json({
                success: false,
                message: 'Database error: ' + error.message
            });
        }

        if (!users || users.length === 0) {
            return res.status(404).json({
                success: false,
                message: 'User not found in database',
                email: userEmail
            });
        }

        const user = users[0];

        // Create session token (simple JWT-like token)
        const sessionToken = Buffer.from(JSON.stringify({
            user_id: user.id,
            email: user.email,
            name: user.name || user.email,
            face_name,
            timestamp: Date.now()
        })).toString('base64');

        return res.status(200).json({
            success: true,
            message: 'Login successful',
            user: {
                id: user.id,
                email: user.email,
                name: user.name || user.email,
                face_name
            },
            session_token: sessionToken
        });

    } catch (error) {
        console.error('Server error:', error);
        return res.status(500).json({
            success: false,
            message: 'Server error: ' + error.message
        });
    }
}
