export default async function handler(req, res) {
    // Enable CORS
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

    if (req.method === 'OPTIONS') {
        return res.status(200).end();
    }

    try {
        const authHeader = req.headers.authorization;

        if (!authHeader || !authHeader.startsWith('Bearer ')) {
            return res.status(401).json({ logged_in: false });
        }

        const token = authHeader.split(' ')[1];

        // Decode session token
        const sessionData = JSON.parse(
            Buffer.from(token, 'base64').toString('utf-8')
        );

        // Check if token is expired (24 hours)
        const tokenAge = Date.now() - sessionData.timestamp;
        const maxAge = 24 * 60 * 60 * 1000; // 24 hours

        if (tokenAge > maxAge) {
            return res.status(401).json({
                logged_in: false,
                message: 'Session expired'
            });
        }

        return res.status(200).json({
            logged_in: true,
            user: {
                id: sessionData.user_id,
                email: sessionData.email,
                name: sessionData.name,
                face_name: sessionData.face_name
            }
        });

    } catch (error) {
        console.error('Check session error:', error);
        return res.status(401).json({ logged_in: false });
    }
}
